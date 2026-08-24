<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RppController;
use App\Http\Controllers\TokenPurchaseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TokenPackageController;
use Illuminate\Support\Facades\Route;

// Landing Page
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

// Midtrans Webhook Callback (Public POST route)
Route::post('/api/midtrans/callback', [TokenPurchaseController::class, 'midtransCallback'])->name('midtrans.callback');

// Authenticated User Dashboard Redirect
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('rpps.index');
})->middleware(['auth'])->name('dashboard');

// User Authenticated Routes
Route::middleware('auth')->group(function () {
    // CRUD RPP Vokasi
    Route::resource('rpps', RppController::class);

    // Profil & Token Kuota User
    Route::get('/tokens', [TokenPurchaseController::class, 'index'])->name('tokens.index');
    Route::post('/tokens/checkout/{package}', [TokenPurchaseController::class, 'checkout'])->name('tokens.checkout');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin CMS Routes (Admin Only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // 1. Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. List User & Topup Token Manual
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/topup', [UserController::class, 'topupTokens'])->name('users.topup');
    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');

    // 3. Paket Token CRUD
    Route::resource('packages', TokenPackageController::class)->except(['create', 'edit', 'show']);
});

require __DIR__.'/auth.php';
