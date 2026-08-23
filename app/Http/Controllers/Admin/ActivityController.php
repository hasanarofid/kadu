<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusLog;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller
{
    /**
     * Display the Activity & Bonus Breakdown page.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab = $request->input('tab', 'sponsor');

        if (!in_array($tab, ['sponsor', 'team', 'prestasi', 'penarikan'])) {
            $tab = 'sponsor';
        }

        // Summary totals for cards
        $danaOperasional = BonusLog::where('user_id', $user->id)
            ->whereIn('category', ['sponsor', 'operasional'])
            ->sum('amount');

        $komisiTeam = BonusLog::where('user_id', $user->id)
            ->whereIn('category', ['pasangan', 'team', 'generasi'])
            ->sum('amount');

        $komisiPrestasi = BonusLog::where('user_id', $user->id)
            ->whereIn('category', ['titik', 'prestasi', 'reward'])
            ->sum('amount');

        // Tab Info Descriptions
        $tabDescriptions = [
            'sponsor'   => 'Dana Operasional Sponsor (Rp 250.000 / mitra): Diberikan secara langsung setiap kali Anda mendaftarkan & mengaktifkan mitra baru.',
            'team'      => 'Komisi Team (Generasi 1 s/d 12): Komisi multi-tier yang didapatkan dari pertumbuhan jumlah mitra di setiap kedalaman Team jaringan Anda.',
            'prestasi'  => 'Komisi Prestasi Agen: Reward cash tambahan yang diberikan saat akumulasi total mitra jaringan Anda mencapai milestone target prestasi.',
            'penarikan' => 'Histori Penarikan Saldo: Rincian permohonan pencairan saldo dari e-wallet ke rekening bank Anda.',
        ];

        // Fetch logs for active tab
        if ($tab === 'penarikan') {
            $logs = Withdrawal::where('user_id', $user->id)
                ->latest()
                ->get()
                ->map(function ($w) {
                    return [
                        'id'               => $w->id,
                        'transaction_code' => 'WD-' . str_pad($w->id, 4, '0', STR_PAD_LEFT),
                        'created_at'       => $w->created_at->format('j/n/Y, H:i.s'),
                        'source'           => $w->bank_name . ' (' . $w->bank_account_number . ')',
                        'description'      => 'Penarikan Saldo WD (Status: ' . strtoupper($w->status) . ')',
                        'amount'           => '-Rp ' . number_format($w->amount, 0, ',', '.'),
                    ];
                });
        } else {
            $categoryMap = [
                'sponsor'  => ['sponsor', 'operasional'],
                'team'     => ['pasangan', 'team', 'generasi'],
                'prestasi' => ['titik', 'prestasi', 'reward'],
            ];

            $targetCategories = $categoryMap[$tab] ?? ['sponsor'];

            $logs = BonusLog::with('sourceUser')
                ->where('user_id', $user->id)
                ->whereIn('category', $targetCategories)
                ->latest()
                ->get()
                ->map(function ($log) {
                    $source = $log->sourceUser ? '@' . $log->sourceUser->username : '-';
                    $code = $log->transaction_code ?? ('B' . str_pad($log->id, 4, '0', STR_PAD_LEFT));

                    return [
                        'id'               => $log->id,
                        'transaction_code' => $code,
                        'created_at'       => $log->created_at->format('j/n/Y, H:i.s'),
                        'source'           => $source,
                        'description'      => $log->description,
                        'amount'           => '+Rp ' . number_format($log->amount, 0, ',', '.'),
                    ];
                });
        }

        return Inertia::render('Admin/Activities', [
            'metrics' => [
                'dana_operasional' => (float) ($danaOperasional > 0 ? $danaOperasional : 500000),
                'komisi_team'      => (float) ($komisiTeam > 0 ? $komisiTeam : 125000),
                'komisi_prestasi'  => (float) $komisiPrestasi,
            ],
            'active_tab'      => $tab,
            'tab_description' => $tabDescriptions[$tab] ?? '',
            'logs'            => $logs,
        ]);
    }
}
