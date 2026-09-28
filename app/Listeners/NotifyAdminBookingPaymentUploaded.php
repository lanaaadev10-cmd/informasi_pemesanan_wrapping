<?php

namespace App\Listeners;

use App\Events\BookingPaymentUploaded;
use App\Models\User;
use App\Services\NotifikasiService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

/**
 * Listener: Notify Admin Booking Payment Uploaded
 *
 * Triggered by: BookingPaymentUploaded event
 * Action: Kirim notifikasi Filament ke semua admin untuk verifikasi
 */
class NotifyAdminBookingPaymentUploaded
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingPaymentUploaded $event): void
    {
        $payment = $event->payment;
        $booking = $payment->booking;
        $customer = $booking->user;

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $filNotif = Notification::make()
                ->title('Bukti Pembayaran Booking oleh ' . $customer->name)
                ->body(
                    "Booking #{$booking->booking_code}\n" .
                    "Paket: {$booking->layanan?->nama_layanan}\n" .
                    "Total: Rp " . number_format((float) $payment->amount, 0, ',', '.') . "\n" .
                    "Mohon verifikasi segera."
                )
                ->icon('heroicon-o-document-check')
                ->success();

            NotificationFacade::sendNow($admin, $filNotif->toDatabase());

            $this->notifikasiService->createNotification(
                userId: $admin->id,
                judul: 'Bukti Pembayaran Booking - ' . $customer->name,
                pesan: "Booking #{$booking->booking_code} memerlukan verifikasi pembayaran. Total: Rp " . number_format((float) $payment->amount, 0, ',', '.'),
                tipe: 'pembayaran',
                idPesanan: null,
            );
        }
    }
}