<?php

namespace App\Listeners;

use App\Events\PaymentVerified;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Order Processing Started
 * 
 * Triggered by: PaymentVerified event (payment verified, order = sedang_diproses)
 * Action: Notify customer bahwa order sudah verified dan sedang diproses
 */
class NotifyOrderProcessingStarted
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(PaymentVerified $event): void
    {
        $pesanan = $event->pesanan;
        $user = $pesanan->user;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pembayaran Terverifikasi - Pesanan Sedang Diproses',
            pesan: "Pembayaran pesanan {$pesanan->kode_pesanan} telah terverifikasi. Pesanan Anda sedang diproses oleh tim kami.",
            tipe: 'email',
            idPesanan: $pesanan->id_pesanan,
        );

        $this->notifikasiService->sendNotification($notifikasi);
    }
}
