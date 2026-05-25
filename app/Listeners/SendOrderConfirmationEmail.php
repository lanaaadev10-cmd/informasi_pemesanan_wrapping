<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\NotifikasiService;

/**
 * Listener: Send Order Confirmation Email
 * 
 * Triggered by: OrderCreated event
 * Action: Kirim email ke customer tentang order yang baru dibuat
 */
class SendOrderConfirmationEmail
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(OrderCreated $event): void
    {
        $pesanan = $event->pesanan;
        $user = $pesanan->user;

        // Create email notification
        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pesanan Berhasil Dibuat',
            pesan: "Pesanan Anda dengan kode {$pesanan->kode_pesanan} telah berhasil dibuat. Kode ini digunakan untuk tracking pesanan.",
            tipe: 'email',
            idPesanan: $pesanan->id_pesanan,
        );

        // Send email
        $this->notifikasiService->sendNotification($notifikasi);

        // Also create in-app notification
        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pesanan Berhasil Dibuat',
            pesan: "Pesanan Anda sedang menunggu konfirmasi admin. Periksa email untuk detail lengkap.",
            tipe: 'in_app',
            idPesanan: $pesanan->id_pesanan,
        );
    }
}
