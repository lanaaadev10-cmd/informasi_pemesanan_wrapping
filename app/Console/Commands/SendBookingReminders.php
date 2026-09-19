<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Services\NotifikasiService;
use Illuminate\Console\Command;

/**
 * Kirim pengingat H-1 sebelum jadwal pengerjaan booking.
 *
 * Dijalankan otomatis via scheduler (18:00 WIB) oleh routes/console.php.
 */
class SendBookingReminders extends Command
{
    protected $signature = 'booking:send-reminders';

    protected $description = 'Kirim pengingat H-1 jadwal pengerjaan ke semua user yang punya booking aktif besok';

    public function __construct(
        protected NotifikasiService $notifikasiService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $timezone = 'Asia/Jakarta';
        $tomorrow = now($timezone)->startOfDay()->copy()->addDay();

        $bookings = Booking::query()
            ->with(['user', 'layanan'])
            ->where('booking_date', $tomorrow->toDateString())
            // Hanya booking APPROVED yang belum dikerjakan. Kecualikan
            // IN_PROGRESS agar user tidak dapat reminder berulang.
            ->where('status', BookingStatus::APPROVED->value)
            ->orderBy('booking_date')
            ->get();

        if ($bookings->isEmpty()) {
            $this->info("Tidak ada booking yang perlu diingatkan untuk {$tomorrow->toDateString()}.");
            return self::SUCCESS;
        }

        $sent = 0;
        foreach ($bookings as $booking) {
            $user = $booking->user;
            if (!$user) {
                continue;
            }

            try {
                $this->notifikasiService->createNotification(
                    userId: $user->id,
                    judul: 'Pengingat Jadwal Pengerjaan',
                    pesan: "Halo {$user->name}, besok ({$tomorrow->translatedFormat('l, d F Y')}) kendaraan Anda ({$booking->vehicle_name}) dengan paket {$booking->layanan?->nama_layanan} akan dikerjakan. Mohon siapkan kendaraan Anda.",
                    tipe: 'email',
                    idPesanan: null,
                );

                $this->notifikasiService->createNotification(
                    userId: $user->id,
                    judul: 'Pengingat Jadwal Pengerjaan',
                    pesan: "Besok ({$tomorrow->translatedFormat('d M Y')}) kendaraan Anda akan dikerjakan. Booking: {$booking->booking_code}.",
                    tipe: 'in_app',
                    idPesanan: null,
                );

                $this->info("Reminder dikirim ke {$user->email} untuk booking {$booking->booking_code}.");
                $sent++;
            } catch (\Throwable $e) {
                $this->error("Gagal kirim reminder booking {$booking->booking_code}: {$e->getMessage()}");
            }
        }

        $this->info("Selesai. {$sent} reminder terkirim.");

        return self::SUCCESS;
    }
}