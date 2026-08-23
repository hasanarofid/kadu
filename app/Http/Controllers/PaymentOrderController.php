<?php

namespace App\Http\Controllers;

use App\Models\PaymentOrder;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PaymentOrderController extends Controller
{
    /**
     * Halaman form beli voucher publik.
     */
    public function create()
    {
        $voucherPrice = (float) Setting::getValue('voucher_price', 500000);
        $bankInfo = [
            'bank_name'    => Setting::getValue('payment_bank_name', 'BCA'),
            'account_no'   => Setting::getValue('payment_account_no', '-'),
            'account_name' => Setting::getValue('payment_account_name', 'Mitra Syiar Baitullah'),
        ];

        return Inertia::render('BuyVoucher', [
            'voucher_price' => $voucherPrice,
            'bank_info'     => $bankInfo,
        ]);
    }

    /**
     * Simpan order beli voucher + upload bukti transfer.
     */
    public function store(Request $request)
    {
        $voucherPrice = (float) Setting::getValue('voucher_price', 500000);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'voucher_qty'    => 'required|integer|min:1|max:20',
            'transfer_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('transfer_proof')->store('payment-proofs', 'public');

        $userId = auth()->check() ? auth()->id() : null;

        $order = PaymentOrder::create([
            'user_id'       => $userId,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'],
            'voucher_qty'   => $validated['voucher_qty'],
            'amount'        => $voucherPrice * $validated['voucher_qty'],
            'transfer_proof' => $path,
            'status'        => 'pending',
        ]);

        return redirect()->route('payment.status', ['uuid' => $order->uuid]);
    }

    /**
     * Halaman cek status order (publik via UUID).
     */
    public function status(string $uuid)
    {
        $order = PaymentOrder::where('uuid', $uuid)->firstOrFail();

        return Inertia::render('PaymentStatus', [
            'order' => [
                'uuid'           => $order->uuid,
                'name'           => $order->name,
                'email'          => $order->email,
                'voucher_qty'    => $order->voucher_qty,
                'amount'         => (float) $order->amount,
                'status'         => $order->status,
                'admin_notes'    => $order->admin_notes,
                'verified_at'    => $order->verified_at?->format('d/m/Y H:i'),
                'created_at'     => $order->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }
}
