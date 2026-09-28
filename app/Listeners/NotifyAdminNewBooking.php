<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\User;
use App\Services\NotifikasiService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

/**
 * Listener: Notify Admin New Booking
 *
 * Triggered by: BookingCreated event
 * Action: Kirim notifikasi Filament ke semua admin
 */
class NotifyAdminNewBooking
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;
        $customer = $booking->user;

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $filNotif = Notification::make()
                ->title('Calendar Booking Baru dari ' . $customer->name)
                ->body(
                    "Booking #{$booking->booking_code}\n" .
                    "Paket: {$booking->layanan?->nama_layanan}\n" .
                    "Tanggal: {$booking->booking_date->format('d-m-Y')}\n" .
                    "Mohon konfirmasi segera."
                )
                ->icon('heroicon-o-calendar-days')
                ->warning();

            NotificationFacade::sendNow($admin, $filNotif->toDatabase());
        }
    }
}