<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rpp;
use App\Models\TokenPackage;
use App\Models\TokenTransaction;
use App\Models\TokenLog;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard for KADU (Karsa Edukasi Vokasi).
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalRpps = Rpp::count();
        $totalPackages = TokenPackage::where('is_active', true)->count();
        $totalRevenue = TokenTransaction::where('payment_status', 'paid')->sum('amount');
        $totalTokensSold = TokenTransaction::where('payment_status', 'paid')->sum('tokens');

        $recentUsers = User::latest()->take(5)->get();
        $recentRpps = Rpp::with('user')->latest()->take(5)->get();
        $recentTransactions = TokenTransaction::with('user', 'package')->latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => $totalUsers,
                'total_rpps' => $totalRpps,
                'total_packages' => $totalPackages,
                'total_revenue' => $totalRevenue,
                'total_tokens_sold' => $totalTokensSold,
            ],
            'recent_users' => $recentUsers,
            'recent_rpps' => $recentRpps,
            'recent_transactions' => $recentTransactions,
        ]);
    }
}
