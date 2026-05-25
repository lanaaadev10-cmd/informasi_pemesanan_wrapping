<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Order Completed
 * 
 * Triggered by: OrderCompleted event (order status = selesai)
 * Action: Notify customer pesanan sudah selesai
 */
class NotifyOrderCompleted
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(OrderCompleted $event): void
    {
        $pesanan = $event->pesanan;
        $user = $pesanan->user;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pesanan Selesai! 🎉',
            pesan: "Pesanan {$pesanan->kode_pesanan} telah selesai. Terima kasih telah berbelanja dengan kami!",
            tipe: 'email',
            idPesanan: $pesanan->id_pesanan,
        );

        $this->notifikasiService->sendNotification($notifikasi);
    }
}
