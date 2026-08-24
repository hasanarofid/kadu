<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Mail\BonusMlmMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WithdrawalController extends Controller
{
    /**
     * Display the Withdrawal (WD) management page.
     */
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        // Calculate total approved and pending withdrawals
        $baseQuery = Withdrawal::query();
        if (!$isAdmin) {
            $baseQuery->where('user_id', $user->id);
        }

        $totalCair = (clone $baseQuery)->where('status', 'approved')->sum('amount');
        $totalProses = (clone $baseQuery)->where('status', 'pending')->sum('amount');

        // Fetch withdrawals list
        $withdrawalsQuery = Withdrawal::with('user')->latest();
        if (!$isAdmin) {
            $withdrawalsQuery->where('user_id', $user->id);
        }

        $withdrawals = $withdrawalsQuery->get()->map(function ($w) {
            $gross = (float) $w->amount;
            $adminFee = $gross * 0.10; // Potongan Admin 10%
            $umrohSaving = $gross * 0.10; // Potongan Tabungan Umroh 10%
            $netReceived = $gross * 0.80; // Transfer Bersih 80%

            return [
                'id'                  => $w->id,
                'user_name'           => $w->user ? $w->user->name : 'Mitra',
                'user_username'       => $w->user ? $w->user->username : 'mitra',
                'bank_name'           => $w->bank_name,
                'bank_account_number' => $w->bank_account_number,
                'bank_account_name'   => $w->bank_account_name,
                'amount'              => $gross,
                'fee'                 => $adminFee,
                'umroh_saving'        => $umrohSaving,
                'net_received'        => $netReceived,
                'status'              => $w->status, // 'pending', 'approved', 'rejected'
                'admin_notes'         => $w->admin_notes,
                'created_at'          => $w->created_at->format('j/n/Y, H:i.s'),
            ];
        });

        return Inertia::render('Admin/Withdrawals', [
            'wallet' => [
                'saldo'                => (float) ($user->saldo ?? 2500000),
                'saldo_umroh'          => (float) ($user->saldo_umroh ?? 0),
                'min_withdrawal'       => 50000,
                'admin_fee_percent'    => 10, // 10% Admin
                'umroh_saving_percent' => 10, // 10% Tabungan Umroh
                'total_cair'           => (float) $totalCair,
                'total_proses'         => (float) $totalProses,
            ],
            'user_bank' => [
                'bank_name'           => $user->bank_name ?? 'Bank Mandiri',
                'bank_account_number' => $user->bank_account_number ?? '',
                'bank_account_name'   => $user->bank_account_name ?? '',
            ],
            'withdrawals' => $withdrawals,
            'is_admin'    => $isAdmin,
        ]);
    }

    /**
     * Submit a new withdrawal request (WD).
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name'           => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_account_name'   => 'required|string',
            'amount'              => 'required|numeric|min:50000',
        ]);

        $user = auth()->user();
        $minWithdrawal = 50000;

        if ($request->amount < $minWithdrawal) {
            return back()->with('error', 'Nominal penarikan minimum adalah Rp ' . number_format($minWithdrawal, 0, ',', '.') . '!');
        }

        if (($user->saldo ?? 0) < $request->amount) {
            return back()->with('error', 'Saldo E-Wallet Anda tidak mencukupi untuk penarikan sebesar Rp ' . number_format($request->amount, 0, ',', '.') . '!');
        }

        $gross = $request->amount;
        $adminFee = $gross * 0.10;
        $umrohSaving = $gross * 0.10;
        $netReceived = $gross * 0.80;

        DB::transaction(function () use ($user, $request, $gross, $adminFee, $umrohSaving, $netReceived) {
            // Deduct total WD amount from user saldo
            $user->decrement('saldo', $gross);

            // Update user bank info
            $user->update([
                'bank_name'           => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_name'   => $request->bank_account_name,
            ]);

            // Create withdrawal record
            $withdrawal = Withdrawal::create([
                'user_id'             => $user->id,
                'bank_name'           => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_name'   => $request->bank_account_name,
                'amount'              => $gross,
                'fee'                 => $adminFee,
                'status'              => 'pending',
            ]);

            // Record wallet mutation
            WalletTransaction::create([
                'user_id'     => $user->id,
                'type'        => 'out',
                'category'    => 'withdrawal',
                'amount'      => $gross,
                'description' => 'Penarikan Saldo WD #' . $withdrawal->id . ' (Transfer Bersih: Rp ' . number_format($netReceived, 0, ',', '.') . ', Admin 10%: Rp ' . number_format($adminFee, 0, ',', '.') . ', Tabungan Umroh 10%: Rp ' . number_format($umrohSaving, 0, ',', '.') . ')',
            ]);
        });

        return back()->with('success', 'Permohonan penarikan saldo (WD) sebesar Rp ' . number_format($gross, 0, ',', '.') . ' berhasil diajukan! Transfer bersih ke rekening: Rp ' . number_format($netReceived, 0, ',', '.') . ' (Dipotong Admin 10% & Tabungan Umroh 10%).');
    }

    /**
     * Admin approves a withdrawal request.
     */
    public function approve(Withdrawal $withdrawal)
    {
        $admin = auth()->user();

        if (!$admin->hasRole('admin')) {
            return back()->with('error', 'Hanya Admin yang dapat menyetujui permohonan penarikan saldo!');
        }

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permohonan penarikan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status'       => 'approved',
                'processed_at' => now(),
            ]);

            // Add 10% to user's Saldo Tabungan Umroh
            $umrohSaving = $withdrawal->amount * 0.10;
            if ($withdrawal->user) {
                $withdrawal->user->increment('saldo_umroh', $umrohSaving);
            }
        });

        $netReceived = $withdrawal->amount * 0.80;

        // Kirim email notifikasi ke user bahwa penarikan disetujui
        if ($withdrawal->user && $withdrawal->user->email) {
            Mail::to($withdrawal->user->email)->send(new BonusMlmMail($withdrawal->user, 'withdrawal', [
                'status_label' => 'DISETUJUI / DITRANSFER',
                'amount'       => $withdrawal->amount,
                'notes'        => 'Penarikan saldo sebesar Rp ' . number_format($netReceived, 0, ',', '.') . ' (Transfer Bersih) telah berhasil diproses ke rekening ' . $withdrawal->bank_name . ' (' . $withdrawal->bank_account_number . ').',
            ]));
        }

        return back()->with('success', 'Penarikan saldo #' . $withdrawal->id . ' sebesar Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' disetujui! Mitra menerima bersih Rp ' . number_format($netReceived, 0, ',', '.') . ' & Tabungan Umroh +Rp ' . number_format($withdrawal->amount * 0.10, 0, ',', '.') . '.');
    }

    /**
     * Admin rejects a withdrawal request and refunds user balance.
     */
    public function reject(Request $request, Withdrawal $withdrawal)
    {
        $admin = auth()->user();

        if (!$admin->hasRole('admin')) {
            return back()->with('error', 'Hanya Admin yang dapat menolak permohonan penarikan saldo!');
        }

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permohonan penarikan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($withdrawal, $request) {
            $withdrawal->update([
                'status'       => 'rejected',
                'admin_notes'  => $request->notes ?? 'Penarikan ditolak oleh Admin.',
                'processed_at' => now(),
            ]);

            // Refund balance back to user
            if ($withdrawal->user) {
                $withdrawal->user->increment('saldo', $withdrawal->amount);
            }

            // Record refund mutation
            WalletTransaction::create([
                'user_id'     => $withdrawal->user_id,
                'type'        => 'in',
                'category'    => 'withdrawal_refund',
                'amount'      => $withdrawal->amount,
                'description' => 'Pengembalian dana penarikan saldo (WD #' . $withdrawal->id . ' ditolak)',
            ]);
        });

        // Kirim email notifikasi penolakan ke user
        if ($withdrawal->user && $withdrawal->user->email) {
            Mail::to($withdrawal->user->email)->send(new BonusMlmMail($withdrawal->user, 'withdrawal', [
                'status_label' => 'DITOLAK (Saldo Dikembalikan)',
                'amount'       => $withdrawal->amount,
                'notes'        => $request->notes ?? 'Penarikan ditolak oleh Admin. Saldo sebesar Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' telah dikembalikan ke saldo wallet Anda.',
            ]));
        }

        return back()->with('success', 'Penarikan saldo #' . $withdrawal->id . ' ditolak dan dana Rp ' . number_format($withdrawal->amount, 0, ',', '.') . ' telah dikembalikan ke saldo wallet mitra.');
    }
}
