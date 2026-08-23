<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BonusMlmMail;
use App\Models\PaymentOrder;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class VoucherWalletController extends Controller
{
    /**
     * Display DP Awal Management page (previously Voucher/PIN Wallet).
     */
    public function index()
    {
        $user = auth()->user();
        $voucherPrice = (float) (Setting::get('voucher_price', 500000));

        // Count active DP Awal vouchers
        $voucherCount = Voucher::where('user_id', $user->id)->where('status', 'active')->count();

        // Get all DP Awal vouchers owned by user
        $vouchers = Voucher::where('user_id', $user->id)
            ->with('usedBy')
            ->latest()
            ->get()
            ->map(function ($v) {
                $isAvailable = $v->status === 'active';
                $usedByUsername = $v->usedBy ? $v->usedBy->username : 'mitra';
                $usedDate = $v->used_at ? $v->used_at->format('j/n/Y') : $v->updated_at->format('j/n/Y');

                return [
                    'id'          => $v->id,
                    'code'        => $v->code,
                    'created_at'  => $v->created_at->format('j/n/Y'),
                    'status'      => $isAvailable ? 'TERSEDIA' : 'TERPAKAI',
                    'keterangan'  => $isAvailable
                        ? 'Menunggu pendaftaran mitra baru'
                        : 'Diaktifkan oleh mitra ID: @' . $usedByUsername . ' pada ' . $usedDate,
                ];
            });

        // Get available DP Awal vouchers for transfer select options
        $availableVouchers = Voucher::where('user_id', $user->id)
            ->where('status', 'active')
            ->get(['id', 'code']);

        // Get transfer history
        $transfers = VoucherTransfer::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with(['sender', 'recipient'])
            ->latest()
            ->get()
            ->map(function ($t) use ($user) {
                $isSender = $t->sender_id === $user->id;
                $targetName = $isSender 
                    ? ($t->recipient ? $t->recipient->name . ' (@' . $t->recipient->username . ')' : 'Mitra')
                    : ($t->sender ? $t->sender->name . ' (@' . $t->sender->username . ')' : 'Mitra');

                return [
                    'id'           => $t->id,
                    'type'         => $isSender ? 'DIKIRIM' : 'DITERIMA',
                    'keterangan'   => $isSender ? 'Mengirim DP Awal ke ' . $targetName : 'Menerima DP Awal dari ' . $targetName,
                    'created_at'   => $t->created_at->format('j/n/Y, H:i.s'),
                    'voucher_code' => $t->voucher_code,
                ];
            });

        return Inertia::render('Admin/VoucherWallet', [
            'wallet' => [
                'saldo'         => (float) ($user->saldo ?? 2500000),
                'total_bonus'   => (float) ($user->total_bonus ?? 400000),
                'voucher_count' => $voucherCount,
            ],
            'voucher_price'      => $voucherPrice,
            'vouchers'           => $vouchers,
            'available_vouchers' => $availableVouchers,
            'transfers'          => $transfers,
            'is_admin'           => $user->hasRole('admin'),
        ]);
    }

    /**
     * Purchase a DP Awal code using wallet balance.
     */
    public function buy(Request $request)
    {
        $user = auth()->user();
        $price = (float) (Setting::get('voucher_price', 500000));

        if (($user->saldo ?? 2500000) < $price) {
            return back()->with('error', 'Saldo wallet Anda tidak mencukupi untuk membeli DP Awal (Rp ' . number_format($price, 0, ',', '.') . ')!');
        }

        DB::transaction(function () use ($user, $price) {
            if ($user->saldo !== null) {
                $user->decrement('saldo', $price);
            }

            $code = 'INV-DP-' . rand(1000, 9999) . '-' . strtoupper(Str::random(3));

            Voucher::create([
                'code'         => $code,
                'user_id'      => $user->id,
                'package_name' => 'DP Join Rp 500rb',
                'status'       => 'active',
            ]);
        });

        return back()->with('success', 'Berhasil membeli 1 DP Awal Aktivasi!');
    }

    /**
     * Admin action to produce free DP Awal code.
     */
    public function produce(Request $request)
    {
        $admin = auth()->user();

        if (!$admin->hasRole('admin')) {
            return back()->with('error', 'Hanya Admin yang berhak memproduksi DP Awal gratis!');
        }

        $targetUser = $admin;
        if ($request->filled('username')) {
            $targetUser = User::where('username', $request->username)->first();
            if (!$targetUser) {
                return back()->with('error', 'Username penerima tidak ditemukan!');
            }
        }

        DB::transaction(function () use ($targetUser) {
            $code = 'INV-DP-' . rand(1000, 9999) . '-' . strtoupper(Str::random(3));

            Voucher::create([
                'code'         => $code,
                'user_id'      => $targetUser->id,
                'package_name' => 'DP Join Rp 500rb',
                'status'       => 'active',
            ]);
        });

        return back()->with('success', 'Berhasil memproduksi DP Awal Aktivasi gratis untuk @' . $targetUser->username . '!');
    }

    /**
     * Transfer DP Awal code to another member by username.
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'voucher_id'         => 'required|exists:vouchers,id',
            'recipient_username' => 'required|string',
        ]);

        $sender = auth()->user();

        $voucher = Voucher::where('id', $request->voucher_id)
            ->where('user_id', $sender->id)
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            return back()->with('error', 'Kode DP Awal tidak valid atau sudah terpakai!');
        }

        $recipient = User::where('username', $request->recipient_username)->first();

        if (!$recipient) {
            return back()->with('error', 'Username penerima @' . $request->recipient_username . ' tidak ditemukan!');
        }

        if ($recipient->id === $sender->id) {
            return back()->with('error', 'Tidak dapat mentransfer DP Awal ke akun sendiri!');
        }

        DB::transaction(function () use ($voucher, $sender, $recipient) {
            $voucher->update([
                'user_id' => $recipient->id,
            ]);

            VoucherTransfer::create([
                'voucher_id'   => $voucher->id,
                'voucher_code' => $voucher->code,
                'sender_id'    => $sender->id,
                'recipient_id' => $recipient->id,
            ]);
        });

        return back()->with('success', 'Berhasil mentransfer DP Awal ' . $voucher->code . ' ke @' . $recipient->username . '!');
    }

    /**
     * List semua payment orders (admin view).
     */
    public function paymentOrders()
    {
        $orders = PaymentOrder::with(['user', 'verifier'])
            ->latest()
            ->get()
            ->map(fn($o) => [
                'id'             => $o->id,
                'uuid'           => $o->uuid,
                'name'           => $o->name,
                'email'          => $o->email,
                'phone'          => $o->phone,
                'voucher_qty'    => $o->voucher_qty,
                'amount'         => (float) $o->amount,
                'status'         => $o->status,
                'admin_notes'    => $o->admin_notes,
                'transfer_proof' => $o->transfer_proof ? asset('storage/' . $o->transfer_proof) : null,
                'verified_by'    => $o->verifier?->name,
                'verified_at'    => $o->verified_at?->format('d/m/Y H:i'),
                'created_at'     => $o->created_at->format('d/m/Y H:i'),
            ]);

        return Inertia::render('Admin/PaymentOrders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Verifikasi payment order — terbitkan DP Awal otomatis.
     */
    public function verifyPayment(Request $request, PaymentOrder $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order ini sudah diproses sebelumnya.');
        }

        $admin = auth()->user();
        $voucherCodes = [];

        DB::transaction(function () use ($order, $admin, &$voucherCodes) {
            $targetUser = $order->user
                ?? User::where('email', $order->email)->first();

            if ($targetUser) {
                for ($i = 0; $i < $order->voucher_qty; $i++) {
                    $code = 'INV-DP-' . rand(1000, 9999) . '-' . strtoupper(Str::random(3));
                    $voucherCodes[] = $code;
                    Voucher::create([
                        'code'         => $code,
                        'user_id'      => $targetUser->id,
                        'package_name' => 'DP Join Rp 500rb',
                        'status'       => 'active',
                    ]);
                }
            }

            $order->update([
                'status'      => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'admin_notes' => $request->input('admin_notes'),
            ]);
        });

        // Kirim email notifikasi jika ada user terdaftar
        $targetUser = $order->user ?? User::where('email', $order->email)->first();
        if ($targetUser) {
            Mail::to($targetUser->email)->queue(new BonusMlmMail($targetUser, 'payment_verified', [
                'voucher_qty'   => $order->voucher_qty,
                'amount'        => $order->amount,
                'voucher_codes' => $voucherCodes,
            ]));
        }

        return back()->with('success', "Order #{$order->id} ({$order->name}) berhasil diverifikasi. {$order->voucher_qty} Kode DP Awal telah diterbitkan!");
    }

    /**
     * Tolak payment order.
     */
    public function rejectPayment(Request $request, PaymentOrder $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', 'Order ini sudah diproses sebelumnya.');
        }

        $request->validate(['admin_notes' => 'required|string|max:500']);

        $order->update([
            'status'      => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Order #{$order->id} telah ditolak.");
    }
}
