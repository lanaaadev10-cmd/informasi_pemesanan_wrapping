<?php

namespace App\Listeners;

use App\Events\OrderRejected;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Order Rejection
 * 
 * Triggered by: OrderRejected event
 * Action: Notify customer tentang alasan rejection
 */
class NotifyOrderRejection
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(OrderRejected $event): void
    {
        $pesanan = $event->pesanan;
        $user = $pesanan->user;

        $catatan = $pesanan->catatan_admin ?? 'Tidak ada alasan yang diberikan';

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pesanan Ditolak',
            pesan: "Pesanan {$pesanan->kode_pesanan} telah ditolak. Alasan: {$catatan}",
            tipe: 'email',
            idPesanan: $pesanan->id_pesanan,
        );

        $this->notifikasiService->sendNotification($notifikasi);
    }
}
