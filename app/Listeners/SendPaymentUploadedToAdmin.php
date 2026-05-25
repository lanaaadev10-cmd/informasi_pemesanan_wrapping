<?php

namespace App\Listeners;

use App\Events\PaymentUploaded;
use App\Models\User;
use App\Services\NotifikasiService;
use Filament\Notifications\Notification;

/**
 * Listener: Send Payment Uploaded To Admin
 *
 * Triggered by: PaymentUploaded event (customer uploads payment proof)
 * Action: Notify all admins untuk verifikasi pembayaran
 */
class SendPaymentUploadedToAdmin
{
    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {}

    public function handle(PaymentUploaded $event): void
    {
        $pembayaran = $event->pembayaran;
        $pesanan = $pembayaran->pesanan;
        $customer = $pesanan->user;

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $metodeStr = $pembayaran->metode_pembayaran instanceof \App\Enums\PaymentMethod ? $pembayaran->metode_pembayaran->value : $pembayaran->metode_pembayaran;
            $filNotif = Notification::make()
                ->title('💳 Bukti Pembayaran Dikirim oleh ' . $customer->name)
                ->body(
                    "Pesanan #{$pesanan->kode_pesanan} telah mengirim bukti pembayaran.\n" .
                    "Total: Rp " . number_format($pesanan->total_harga, 0, ',', '.') . "\n" .
                    "Metode: {$metodeStr}\n" .
                    "Mohon verifikasi segera."
                )
                ->icon('heroicon-o-document-check')
                ->success();

            \Illuminate\Support\Facades\Notification::sendNow($admin, $filNotif->toDatabase());

            // Juga create in-app notification di database
            $this->notifikasiService->createNotification(
                userId: $admin->id,
                judul: '💳 Bukti Pembayaran - ' . $customer->name,
                pesan: "Pesanan #{$pesanan->kode_pesanan} memerlukan verifikasi pembayaran. Total: Rp " . number_format($pesanan->total_harga, 0, ',', '.'),
                tipe: 'pembayaran',
                idPesanan: $pesanan->id_pesanan,
            );
        }
    }
}
