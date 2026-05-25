<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

/**
 * Listener: Send Order Created To Admin
 *
 * Triggered by: OrderCreated event (customer checkout)
 * Action: Notify all admins tentang pesanan baru
 */
class SendOrderCreatedToAdmin
{
    public function handle(OrderCreated $event): void
    {
        $pesanan = $event->pesanan;
        $customer = $pesanan->user;

        // Use chunk to avoid loading all admins into memory
        User::role('admin')->chunk(100, function ($admins) use ($customer, $pesanan) {
            foreach ($admins as $admin) {
                $filNotif = Notification::make()
                    ->title('🛒 Pesanan Baru dari ' . $customer->name)
                    ->body(
                        "Pesanan #{$pesanan->kode_pesanan} telah dibuat.\n" .
                        "Total: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n" .
                        "Status: Menunggu Konfirmasi"
                    )
                    ->icon('heroicon-o-shopping-bag')
                    ->warning();

                NotificationFacade::sendNow($admin, $filNotif->toDatabase());
            }
        });
    }
}
