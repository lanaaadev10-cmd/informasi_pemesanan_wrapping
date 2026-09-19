<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Booking Created
 *
 * Triggered by: BookingCreated event (customer submits new booking)
 * Action: Kirim pemberitahuan ke customer bahwa booking diterima
 */
class NotifyBookingCreated
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;
        $user = $booking->user;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Booking Berhasil Diajukan',
            pesan: "Booking Anda dengan kode {$booking->booking_code} untuk tanggal {$booking->booking_date->format('d-m-Y')} telah kami terima. Menunggu konfirmasi admin.",
            tipe: 'email',
            idPesanan: null,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Booking Berhasil Diajukan',
            pesan: "Booking {$booking->booking_code} sedang menunggu konfirmasi admin. Pantau status di halaman Booking Anda.",
            tipe: 'in_app',
            idPesanan: null,
        );
    }
}