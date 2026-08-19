<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonusLog;
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

        if (!in_array($tab, ['sponsor', 'pasangan', 'titik', 'reward', 'penarikan'])) {
            $tab = 'sponsor';
        }

        // Summary totals for cards
        $bonusSponsor = BonusLog::where('user_id', $user->id)->where('category', 'sponsor')->sum('amount');
        $bonusPasangan = BonusLog::where('user_id', $user->id)->where('category', 'pasangan')->sum('amount');
        $bonusTitik = BonusLog::where('user_id', $user->id)->where('category', 'titik')->sum('amount');
        $bonusReward = BonusLog::where('user_id', $user->id)->where('category', 'reward')->sum('amount');

        // Tab Info Descriptions
        $tabDescriptions = [
            'sponsor' => 'Bonus Sponsor (100% dari pendaftaran): Diberikan setiap kali Anda mereferensikan secara langsung member baru yang diaktifkan dengan VOUCHER. Nilai per sponsor saat ini: Rp 100.000.',
            'pasangan' => 'Bonus Pasangan (Keseimbangan Kaki): Diberikan saat terjadi pasangan volume omset pada kaki Kiri dan Kanan jaringan Anda.',
            'titik' => 'Bonus Titik RO: Diberikan dari setiap pencapaian transaksi Repeat Order (RO) di jaringan Anda.',
            'reward' => 'Bonus Reward: Diberikan secara otomatis saat akumulasi poin jaringan Anda mencapai target level reward.',
            'penarikan' => 'Histori Penarikan Saldo: Rincian transaksi pencairan saldo dari e-wallet ke rekening bank Anda.',
        ];

        // Fetch logs for active tab
        $logs = BonusLog::with('sourceUser')
            ->where('user_id', $user->id)
            ->where('category', $tab)
            ->latest()
            ->get()
            ->map(function ($log) {
                $source = $log->sourceUser ? '@' . $log->sourceUser->username : '-';
                $code = $log->transaction_code ?? ('B' . str_pad($log->id, 3, '0', STR_PAD_LEFT));

                return [
                    'id' => $log->id,
                    'transaction_code' => $code,
                    'created_at' => $log->created_at->format('j/n/Y, H.i.s'),
                    'source' => $source,
                    'description' => $log->description,
                    'amount' => '+Rp ' . number_format($log->amount, 0, ',', '.'),
                ];
            });

        return Inertia::render('Admin/Activities', [
            'metrics' => [
                'bonus_sponsor' => (float) ($bonusSponsor > 0 ? $bonusSponsor : 300000),
                'bonus_pasangan' => (float) ($bonusPasangan > 0 ? $bonusPasangan : 100000),
                'bonus_titik' => (float) $bonusTitik,
                'bonus_reward' => (float) $bonusReward,
            ],
            'active_tab' => $tab,
            'tab_description' => $tabDescriptions[$tab] ?? '',
            'logs' => $logs,
        ]);
    }
}
