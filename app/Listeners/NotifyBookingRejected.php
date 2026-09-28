<?php

namespace App\Listeners;

use App\Events\BookingRejected;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Booking Rejected
 *
 * Triggered by: BookingRejected event
 * Action: Kirim pemberitahuan ke customer beserta alasan
 */
class NotifyBookingRejected
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingRejected $event): void
    {
        $booking = $event->booking;
        $user = $booking->user;

        $alasan = $event->alasan ?? $booking->admin_notes ?? 'Tidak ada alasan yang diberikan';

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Booking Ditolak',
            pesan: "Booking {$booking->booking_code} telah ditolak. Alasan: {$alasan}",
            tipe: 'email',
            idPesanan: null,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Booking Ditolak',
            pesan: $alasan,
            tipe: 'sistem',
            idPesanan: null,
        );
    }
}