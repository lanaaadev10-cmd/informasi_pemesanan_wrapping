<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Booking Confirmed
 *
 * Triggered by: BookingConfirmed event (admin confirms booking)
 * Action: Kirim pemberitahuan ke customer untuk segera membayar
 */
class NotifyBookingConfirmed
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingConfirmed $event): void
    {
        $booking = $event->booking;
        $user = $booking->user;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Booking Dikonfirmasi',
            pesan: "Booking {$booking->booking_code} telah dikonfirmasi. Mohon unggah bukti pembayaran untuk mengunci jadwal Anda.",
            tipe: 'email',
            idPesanan: null,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Booking Dikonfirmasi',
            pesan: "Silakan unggah bukti pembayaran pada halaman booking {$booking->booking_code}.",
            tipe: 'pembayaran',
            idPesanan: null,
        );
    }
}