<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BonusLog;
use App\Models\Withdrawal;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display the Reports dashboard.
     */
    public function index(Request $request)
    {
        $type = $request->input('type', 'member');
        if (!in_array($type, ['member', 'bonus', 'pencairan', 'topup'])) {
            $type = 'member';
        }

        $data = $this->getReportData($type);

        return Inertia::render('Admin/Reports', [
            'active_type' => $type,
            'report_data' => $data,
        ]);
    }

    /**
     * Get array data based on report type.
     */
    private function getReportData($type)
    {
        if ($type === 'member') {
            return User::with('parent')->orderBy('id', 'asc')->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'username' => $u->username ?? 'user' . $u->id,
                    'sponsor' => $u->parent ? 'USR' . str_pad($u->parent->id, 3, '0', STR_PAD_LEFT) : '-',
                    'left_count' => (int) ($u->left_count ?? 0),
                    'right_count' => (int) ($u->right_count ?? 0),
                    'saldo' => (float) ($u->saldo ?? 0),
                    'created_at' => $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-',
                ];
            });
        }

        if ($type === 'bonus') {
            return BonusLog::with(['user', 'sourceUser'])->latest()->get()->map(function ($b) {
                return [
                    'id' => $b->id,
                    'name' => $b->user ? $b->user->name : 'Member',
                    'username' => $b->user ? $b->user->username : 'user',
                    'category' => ucfirst($b->category),
                    'source' => $b->sourceUser ? '@' . $b->sourceUser->username : '-',
                    'description' => $b->description,
                    'amount' => (float) $b->amount,
                    'created_at' => $b->created_at ? $b->created_at->format('d/m/Y H:i') : '-',
                ];
            });
        }

        if ($type === 'pencairan') {
            return Withdrawal::with('user')->latest()->get()->map(function ($w) {
                return [
                    'id' => $w->id,
                    'name' => $w->user ? $w->user->name : 'Member',
                    'username' => $w->user ? $w->user->username : 'user',
                    'bank_name' => $w->bank_name,
                    'bank_account_number' => $w->bank_account_number,
                    'bank_account_name' => $w->bank_account_name,
                    'amount' => (float) $w->amount,
                    'status' => strtoupper($w->status),
                    'created_at' => $w->created_at ? $w->created_at->format('d/m/Y H:i') : '-',
                ];
            });
        }

        if ($type === 'topup') {
            return WalletTransaction::with('user')->latest()->get()->map(function ($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->user ? $t->user->name : 'Member',
                    'username' => $t->user ? $t->user->username : 'user',
                    'category' => ucfirst($t->category),
                    'description' => $t->description,
                    'type' => $t->type === 'in' ? 'MASUK' : 'KELUAR',
                    'amount' => (float) $t->amount,
                    'created_at' => $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-',
                ];
            });
        }

        return [];
    }

    /**
     * Export data to Excel (CSV compatible format with UTF-8 BOM).
     */
    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'member');
        $filename = "Laporan_" . ucfirst($type) . "_" . date('Y-m-d') . ".csv";

        $headers = [
            "Content-Type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($type) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Microsoft Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            if ($type === 'member') {
                fputcsv($file, ['ID', 'Nama Lengkap', 'Username', 'Sponsor', 'Titik Kiri', 'Titik Kanan', 'Saldo Wallet (Rp)', 'Tanggal Daftar']);
                $rows = User::with('parent')->orderBy('id', 'asc')->get();
                foreach ($rows as $u) {
                    fputcsv($file, [
                        'USR' . str_pad($u->id, 3, '0', STR_PAD_LEFT),
                        $u->name,
                        $u->username,
                        $u->parent ? 'USR' . str_pad($u->parent->id, 3, '0', STR_PAD_LEFT) : '-',
                        $u->left_count ?? 0,
                        $u->right_count ?? 0,
                        $u->saldo ?? 0,
                        $u->created_at ? $u->created_at->format('d/m/Y H:i') : '-'
                    ]);
                }
            } elseif ($type === 'bonus') {
                fputcsv($file, ['ID', 'Nama Member', 'Username', 'Jenis Bonus', 'Sumber Member', 'Deskripsi', 'Nominal (Rp)', 'Tanggal']);
                $rows = BonusLog::with(['user', 'sourceUser'])->latest()->get();
                foreach ($rows as $b) {
                    fputcsv($file, [
                        'B' . str_pad($b->id, 3, '0', STR_PAD_LEFT),
                        $b->user ? $b->user->name : '-',
                        $b->user ? $b->user->username : '-',
                        ucfirst($b->category),
                        $b->sourceUser ? '@' . $b->sourceUser->username : '-',
                        $b->description,
                        $b->amount,
                        $b->created_at ? $b->created_at->format('d/m/Y H:i') : '-'
                    ]);
                }
            } elseif ($type === 'pencairan') {
                fputcsv($file, ['ID', 'Nama Member', 'Username', 'Bank Tujuan', 'No. Rekening', 'Pemilik Rekening', 'Nominal WD (Rp)', 'Status', 'Tanggal']);
                $rows = Withdrawal::with('user')->latest()->get();
                foreach ($rows as $w) {
                    fputcsv($file, [
                        'WD' . str_pad($w->id, 3, '0', STR_PAD_LEFT),
                        $w->user ? $w->user->name : '-',
                        $w->user ? $w->user->username : '-',
                        $w->bank_name,
                        $w->bank_account_number,
                        $w->bank_account_name,
                        $w->amount,
                        strtoupper($w->status),
                        $w->created_at ? $w->created_at->format('d/m/Y H:i') : '-'
                    ]);
                }
            } elseif ($type === 'topup') {
                fputcsv($file, ['ID', 'Nama Member', 'Username', 'Kategori', 'Tipe', 'Deskripsi', 'Nominal (Rp)', 'Tanggal']);
                $rows = WalletTransaction::with('user')->latest()->get();
                foreach ($rows as $t) {
                    fputcsv($file, [
                        'TX' . str_pad($t->id, 3, '0', STR_PAD_LEFT),
                        $t->user ? $t->user->name : '-',
                        $t->user ? $t->user->username : '-',
                        ucfirst($t->category),
                        $t->type === 'in' ? 'MASUK' : 'KELUAR',
                        $t->description,
                        $t->amount,
                        $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export data to PDF / Printable View.
     */
    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'member');
        $data = $this->getReportData($type);
        $title = "Laporan " . ucfirst($type);
        $date = date('d F Y, H:i');

        return response()->view('reports.pdf', compact('title', 'type', 'data', 'date'));
    }
}
