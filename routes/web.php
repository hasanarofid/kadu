<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentOrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Web Migration & Seeder Script Runner (Public Route for Hosting Deployment)
Route::get('/run-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        return "<h1>✅ BERHASIL! Database Migration & Seeder Selesai.</h1><pre>" . \Illuminate\Support\Facades\Artisan::output() . "</pre><br><a href='/'>Ke Landing Page KADU</a> | <a href='/login'>Ke Halaman Login</a>";
    } catch (\Throwable $e) {
        return "<h1>❌ Error: " . htmlspecialchars($e->getMessage()) . "</h1><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
});

// Publik — Order Beli Voucher via Transfer Manual (tanpa login)
Route::get('/beli-voucher', [PaymentOrderController::class, 'create'])->name('payment.create');
Route::post('/beli-voucher', [PaymentOrderController::class, 'store'])->name('payment.store');
Route::get('/beli-voucher/status/{uuid}', [PaymentOrderController::class, 'status'])->name('payment.status');

Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('rpps.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('rpps', \App\Http\Controllers\RppController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/banks', [ProfileController::class, 'updateBanks'])->name('profile.update-banks');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin CMS Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Pohon Jaringan (Genealogy Binary Tree)
    Route::get('/pohon-jaringan', [\App\Http\Controllers\Admin\GenealogyController::class, 'index'])->name('pohon-jaringan');

    // Team Saya (Multi-Tier Team Generation Level 1-12)
    Route::get('/team', [\App\Http\Controllers\Admin\TeamController::class, 'index'])->name('team.index');

    // Aktivasi Member Baru
    Route::get('/aktivasi-member', [\App\Http\Controllers\Admin\MemberActivationController::class, 'index'])->name('activation.index');
    Route::post('/aktivasi-member', [\App\Http\Controllers\Admin\MemberActivationController::class, 'store'])->name('activation.store');

    // Voucher / PIN Wallet
    Route::get('/voucher-wallet', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'index'])->name('voucher-wallet.index');
    Route::post('/voucher-wallet/buy', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'buy'])->name('voucher-wallet.buy');
    Route::post('/voucher-wallet/produce', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'produce'])->name('voucher-wallet.produce');
    Route::post('/voucher-wallet/transfer', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'transfer'])->name('voucher-wallet.transfer');

    // Kelola Order Pembayaran Manual (Bukti Transfer)
    Route::get('/payment-orders', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'paymentOrders'])->name('payment-orders.index');
    Route::post('/payment-orders/{order}/verify', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'verifyPayment'])->name('payment-orders.verify');
    Route::post('/payment-orders/{order}/reject', [\App\Http\Controllers\Admin\VoucherWalletController::class, 'rejectPayment'])->name('payment-orders.reject');

    // Keuangan (Finance & E-Wallet Management)
    Route::get('/keuangan', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
    Route::post('/keuangan/cashout', [\App\Http\Controllers\Admin\FinanceController::class, 'cashoutBonus'])->name('finance.cashout');
    Route::post('/keuangan/topup-admin', [\App\Http\Controllers\Admin\FinanceController::class, 'topupAdmin'])->name('finance.topup-admin');
    Route::post('/keuangan/transfer', [\App\Http\Controllers\Admin\FinanceController::class, 'transfer'])->name('finance.transfer');

    // Penarikan Saldo (Withdrawals / WD)
    Route::get('/penarikan-saldo', [\App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/penarikan-saldo', [\App\Http\Controllers\Admin\WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::post('/penarikan-saldo/{withdrawal}/approve', [\App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/penarikan-saldo/{withdrawal}/reject', [\App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Data Jaringan (Member Directory & Impersonation)
    Route::get('/data-jaringan', [\App\Http\Controllers\Admin\NetworkDataController::class, 'index'])->name('network-data.index');
    Route::post('/data-jaringan/impersonate/{user}', [\App\Http\Controllers\Admin\NetworkDataController::class, 'impersonate'])->name('network-data.impersonate');
    Route::post('/data-jaringan/stop-impersonating', [\App\Http\Controllers\Admin\NetworkDataController::class, 'stopImpersonating'])->name('network-data.stop-impersonating');

    // Aktivitas (Activity & Bonus Breakdown)
    Route::get('/aktivitas', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');

    // Laporan (Reports & Excel/PDF Exports)
    Route::get('/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export-excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/laporan/export-pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('reports.export-pdf');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/rewards', [SettingController::class, 'updateRewards'])->name('settings.rewards');

    // Backup Data (JSON)
    Route::get('/backup-data-json', [\App\Http\Controllers\Admin\BackupController::class, 'downloadJson'])->name('backup-json');

    // Pages
    Route::resource('pages', PageController::class);
    Route::put('pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.update');

    // Posts & Categories
    Route::resource('posts', PostController::class);
    Route::post('categories', [PostController::class, 'storeCategory'])->name('categories.store');
    Route::delete('categories/{category}', [PostController::class, 'destroyCategory'])->name('categories.destroy');
});

require __DIR__.'/auth.php';
