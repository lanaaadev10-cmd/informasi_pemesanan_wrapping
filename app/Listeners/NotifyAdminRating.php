<?php

namespace App\Listeners;

use App\Events\RatingCreated;
use App\Events\RatingUpdated;
use App\Models\User;
use App\Services\NotifikasiService;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

/**
 * Listener: Notify Admin Rating
 *
 * Dipicu oleh event RatingCreated & RatingUpdated.
 * Mengirim notifikasi ke semua admin: baris `notifikasis` (in-app)
 * + Filament database notification.
 */
class NotifyAdminRating
{
    public function handle(RatingCreated|RatingUpdated $event): void
    {
        $rating = $event->rating;
        $customer = $rating->user;
        $layanan = $rating->layanan;

        $judul = '⭐ Rating Baru dari '.($customer->name ?? 'Customer');
        $pesan = sprintf(
            "%s memberi %d bintang untuk layanan %s.\nUlasan: %s",
            $customer->name ?? 'Customer',
            $rating->rating,
            $layanan->nama_layanan ?? 'Layanan',
            $rating->ulasan ?: '-'
        );

        // In-app notification pada baris `notifikasis` untuk setiap admin.
        $notifService = app(NotifikasiService::class);

        // Jangan sampai kegagalan notifikasi memblokir penyimpanan rating.
        try {
            $admins = User::role('admin')->get();
        } catch (\Throwable $e) {
            return;
        }

        $admins->each(function ($admin) use ($notifService, $judul, $pesan, $rating) {
            $notifService->createNotification(
                $admin->id,
                $judul,
                $pesan,
                'info',
                $rating->id_pesanan,
            );

            $filNotif = Notification::make()
                ->title($judul)
                ->body($pesan)
                ->icon('heroicon-o-star')
                ->warning();

            NotificationFacade::sendNow($admin, $filNotif->toDatabase());
        });
    }
}
