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
            bookingId: $booking->id,
        );

        $this->notifikasiService->sendNotification($notifikasi);

        $this->notifikasiService->createNotification(
            userId: $user->id,
            judul: 'Pengerjaan Selesai & Garansi Aktif',
            pesan: "Kendaraan Anda siap diambil! Kartu Garansi Digital (E-Warranty) telah aktif untuk booking {$booking->booking_code}.",
            tipe: 'in_app',
            idPesanan: null,
            bookingId: $booking->id,
        );

        // Terbitkan Kartu Garansi Digital Resmi (E-Warranty)
        \App\Models\EWarranty::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'warranty_code'   => 'WRN-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
                'warranty_type'   => 'Garansi Pemasangan & Lem Resmi Dantie',
                'duration_months' => 12,
                'start_date'      => now()->toDateString(),
                'end_date'        => now()->addMonths(12)->toDateString(),
                'terms'           => "1. Garansi mencakup pengelupasan lem, gelembung udara, dan sambungan sudut selama 12 bulan.\n2. Garansi tidak mencakup kerusakan akibat benturan fisik, gesekan benda tajam, atau cuci steam tekanan tinggi tepat di ujung sambungan stiker.\n3. Wajib menunjukkan kode garansi atau kartu garansi digital resmi Dantie saat klaim.",
                'status'          => 'active',
            ]
        );
    }
}