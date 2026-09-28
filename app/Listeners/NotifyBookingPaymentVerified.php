<?php

namespace App\Listeners;

use App\Events\BookingPaymentVerified;
use App\Services\NotifikasiService;

/**
 * Listener: Notify Booking Payment Verified
 *
 * Triggered by: BookingPaymentVerified event (admin verifies payment)
 * Action: Kirim pemberitahuan ke customer bahwa booking disetujui
 */
class NotifyBookingPaymentVerified
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingPaymentVerified $event): void
    {
        $booking = $event->booking;
        $user = $booking->user;

        $notifikasi = $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pembayaran Terverifikasi',
            pesan: "Pembayaran booking {$booking->booking_code} telah terverifikasi. Jadwal Anda untuk {$booking->booking_date->format('d-m-Y')} terkunci.",
            tipe: 'email',
            idPesanan: null,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pembayaran Terverifikasi',
            pesan: "Booking {$booking->booking_code} disetujui. Akan segera dikerjakan.",
            tipe: 'in_app',
            idPesanan: null,
        );
    }
}