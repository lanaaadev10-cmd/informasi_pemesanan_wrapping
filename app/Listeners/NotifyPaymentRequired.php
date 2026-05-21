<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Payment Required
 * 
 * Triggered by: OrderConfirmed event (order status = menunggu_pembayaran)
 * Action: Notify customer untuk upload bukti pembayaran
 */
class NotifyPaymentRequired
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(OrderConfirmed $event): void
    {
        $pesanan = $event->pesanan;
        $user = $pesanan->user;
        $form = $pesanan->form;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pesanan Dikonfirmasi - Silakan Lakukan Pembayaran',
            pesan: "Pesanan {$pesanan->kode_pesanan} telah dikonfirmasi. Total pembayaran: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . 
                 ". Silakan upload bukti transfer melalui aplikasi.",
            tipe: 'email',
            idPesanan: $pesanan->id_pesanan,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        // SMS notification jika nomor HP ada
        if ($user->phone) {
            $this->notifikasiService->createNotification(
                userId: $user->id,
                judul: 'Pesanan Dikonfirmasi',
                pesan: "Pesanan {$pesanan->kode_pesanan} dikonfirmasi. Total: Rp " . number_format($pesanan->total_harga, 0, ',', '.'),
                tipe: 'sms',
                idPesanan: $pesanan->id_pesanan,
            );
        }
    }
}
