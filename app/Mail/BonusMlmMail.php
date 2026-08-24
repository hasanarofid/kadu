<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BonusMlmMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param User $user
     * @param string $type   'sponsor' | 'team' | 'reward' | 'payment_verified'
     * @param array  $data   Data tambahan konteks notifikasi
     */
    public function __construct(
        public User $user,
        public string $type,
        public array $data = []
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'sponsor'          => 'Bonus Ujroh Sponsor Masuk!',
            'team'             => 'Bonus Komisi Team Masuk!',
            'reward'           => 'Selamat! Reward Prestasi Anda Telah Diproses!',
            'payment_verified' => 'Pembayaran Voucher Terverifikasi - Voucher Siap Digunakan!',
            'payment_rejected' => 'Status Pembayaran Voucher Anda',
            'token_purchase'   => 'Pembelian Token AI RPP Berhasil!',
            'withdrawal'       => 'Update Status Penarikan Saldo Anda',
        ];

        return new Envelope(subject: $subjects[$this->type] ?? 'Notifikasi Mitra Syiar Baitullah');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.bonus-mlm', with: [
            'user' => $this->user,
            'type' => $this->type,
            'data' => $this->data,
        ]);
    }
}
