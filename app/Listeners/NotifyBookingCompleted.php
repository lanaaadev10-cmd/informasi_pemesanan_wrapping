<?php

namespace App\Listeners;

use App\Events\BookingCompleted;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Booking Completed
 *
 * Triggered by: BookingCompleted event
 * Action: Kirim pemberitahuan ke customer bahwa pengerjaan selesai
 */
class NotifyBookingCompleted
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingCompleted $event): void
    {
        $booking = $event->booking;
        $user = $booking->user;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pengerjaan Selesai',
            pesan: "Booking {$booking->booking_code} telah selesai dikerjakan. Terima kasih telah menggunakan layanan kami!",
            tipe: 'email',
            idPesanan: null,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pengerjaan Selesai',
            pesan: "Kendaraan Anda siap diambil. Booking {$booking->booking_code}.",
            tipe: 'in_app',
            idPesanan: null,
        );
    }
}