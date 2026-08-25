<?php

namespace App\Http\Controllers;

use App\Models\TokenPackage;
use App\Models\TokenTransaction;
use App\Models\TokenLog;
use App\Mail\BonusMlmMail;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TokenPurchaseController extends Controller
{
    /**
     * Show available token packages and purchase history for logged in user.
     */
    public function index()
    {
        $user = auth()->user();

        $packages = TokenPackage::where('is_active', true)->get();
        $transactions = TokenTransaction::with('package')->where('user_id', $user->id)->latest()->take(10)->get();
        $logs = TokenLog::where('user_id', $user->id)->latest()->take(15)->get();

        return Inertia::render('Token/Purchase', [
            'userTokens' => $user->tokens ?? 0,
            'packages' => $packages,
            'transactions' => $transactions,
            'logs' => $logs,
        ]);
    }

    /**
     * Initiate Midtrans payment checkout for a Token Package.
     */
    public function checkout(Request $request, TokenPackage $package)
    {
        $user = auth()->user();
        $orderId = 'KADU-TKN-' . strtoupper(Str::random(6)) . '-' . time();

        $transaction = TokenTransaction::create([
            'user_id' => $user->id,
            'token_package_id' => $package->id,
            'order_id' => $orderId,
            'tokens' => $package->tokens,
            'amount' => $package->price,
            'payment_status' => 'pending',
            'payment_method' => 'midtrans',
        ]);

        // Midtrans Snap integration configuration
        $serverKey = env('MIDTRANS_SERVER_KEY', '');
        $isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', true), FILTER_VALIDATE_BOOLEAN);

        if (!empty($serverKey)) {
            try {
                MidtransConfig::$serverKey = $serverKey;
                MidtransConfig::$isProduction = $isProduction;
                MidtransConfig::$isSanitized = true;
                MidtransConfig::$is3ds = true;

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $package->price,
                    ],
                    'item_details' => [
                        [
                            'id' => (string) $package->id,
                            'price' => (int) $package->price,
                            'quantity' => 1,
                            'name' => substr($package->name . ' (' . $package->tokens . ' Token RPP)', 0, 50),
                        ]
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                ];

                $snapToken = MidtransSnap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);

                return redirect()->back()->with('snap_token', $snapToken)->with('success', 'Order Token berhasil dibuat! Silakan tuntaskan pembayaran.');
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans Snap Checkout Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal membuat tagihan Midtrans: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Order Token berhasil dibuat! Silakan hubungi Admin untuk verifikasi.');
    }

    /**
     * Handle Midtrans Payment Webhook Callback.
     */
    public function midtransCallback(Request $request)
    {
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $transactionStatus = $request->transaction_status;

        $transaction = TokenTransaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Order not found'], 444);
        }

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            if ($transaction->payment_status !== 'paid') {
                $transaction->update(['payment_status' => 'paid']);

                // Credit tokens to user
                $user = $transaction->user;
                $user->increment('tokens', $transaction->tokens);

                TokenLog::create([
                    'user_id' => $user->id,
                    'rpp_id' => null,
                    'type' => 'purchase',
                    'tokens' => $transaction->tokens,
                    'balance_after' => $user->fresh()->tokens,
                    'description' => "Pembelian {$transaction->tokens} Token via Midtrans ({$transaction->order_id})",
                ]);

                // Kirim Notifikasi Email Pembelian Token Berhasil
                if ($user && $user->email) {
                    Mail::to($user->email)->send(new BonusMlmMail($user, 'token_purchase', [
                        'tokens'       => $transaction->tokens,
                        'amount'       => $transaction->amount,
                        'order_id'     => $transaction->order_id,
                        'package_name' => $transaction->package?->name ?? 'Paket Token RPP',
                        'total_tokens' => $user->tokens,
                    ]));
                }
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->update(['payment_status' => 'failed']);
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
