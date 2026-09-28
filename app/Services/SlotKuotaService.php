<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 * SlotKuotaService — Pengelola Kuota Slot Harian Terpusat
 * ============================================================
 *
 * KONSEP UTAMA:
 * Bengkel hanya menerima 5 kendaraan per hari.
 * Kuota ini DIPAKAI BERSAMA oleh:
 *   - Fitur BOOKING  (reservasi jadwal via kalender)
 *   - Fitur PESANAN  (pesan langsung / checkout keranjang)
 *
 * Artinya: jika ada 3 booking + 2 pesanan = PENUH (5/5).
 * Tidak ada pemisahan kuota antara dua fitur tersebut.
 *
 * Alasan menggunakan service terpisah (bukan ditaruh di masing-masing
 * service):
 *   - Single Responsibility: satu tempat untuk aturan bisnis kuota
 *   - DRY: tidak ada duplikasi konstanta MAX_PER_DAY
 *   - Testability: bisa di-mock dengan mudah
 *   - Extensibility: mudah ditambah fitur (misal: kuota per jenis layanan)
 */
class SlotKuotaService
{
    /**
     * Batas maksimum kendaraan yang diterima per hari (gabungan booking + pesanan).
     */
    public const MAX_SLOT_PER_DAY = 5;

    /**
     * Cache TTL untuk kuota (detik). Cukup 60 detik karena data berubah relatif sering.
     */
    protected const CACHE_TTL = 60;

    /**
     * Status booking yang dianggap "aktif" / mengisi slot.
     */
    public const BOOKING_ACTIVE_STATUSES = [
        'pending',
        'confirmed',
        'awaiting_payment',
        'payment_uploaded',
        'approved',
        'in_progress',
    ];

    /**
     * Status pesanan yang dianggap "aktif" / mengisi slot.
     */
    public const PESANAN_ACTIVE_STATUSES = [
        'menunggu_konfirmasi_admin',
        'menunggu_pembayaran',
        'pembayaran_diproses',
        'sedang_diproses',
    ];

    /**
     * -------------------------------------------------------
     * Cek kuota untuk tanggal tertentu.
     *
     * @return array{
     *   available: int,
     *   is_full: bool,
     *   booked_count: int,
     *   pesanan_count: int,
     *   total_used: int,
     *   max: int,
     * }
     * -------------------------------------------------------
     */
    public function checkQuota(string $date): array
    {
        $cacheKey = "slot_quota_{$date}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($date) {
            // Hitung booking aktif untuk tanggal ini
            $bookedCount = Booking::query()
                ->whereDate('booking_date', $date)
                ->whereIn('status', self::BOOKING_ACTIVE_STATUSES)
                ->count();

            // Hitung pesanan aktif (baik dari booking_date di pesanans atau jadwal_pengerjaan di form_pesanans)
            $pesananCount = Pesanan::query()
                ->where(function ($q) use ($date) {
                    $q->whereDate('booking_date', $date)
                      ->orWhereHas('form', function ($fq) use ($date) {
                          $fq->whereDate('jadwal_pengerjaan', $date);
                      });
                })
                ->whereIn('status', self::PESANAN_ACTIVE_STATUSES)
                ->count();

            $totalUsed = $bookedCount + $pesananCount;
            $available = max(self::MAX_SLOT_PER_DAY - $totalUsed, 0);

            return [
                'available'     => $available,
                'is_full'       => $available <= 0,
                'booked_count'  => $bookedCount,   // dari fitur booking
                'pesanan_count' => $pesananCount,  // dari fitur pesanan
                'total_used'    => $totalUsed,
                'max'           => self::MAX_SLOT_PER_DAY,
            ];
        });
    }

    /**
     * Cek kuota hari ini (shorthand).
     */
    public function getTodayQuota(): array
    {
        return $this->checkQuota(now()->toDateString());
    }

    /**
     * Kuota per tanggal dalam satu bulan (untuk kalender landing page).
     *
     * @return array<string, array>
     */
    public function getMonthQuota(int $year, int $month): array
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end   = date('Y-m-t', strtotime($start));

        // Agregat booking per tanggal
        $bookingData = Booking::query()
            ->selectRaw('DATE(booking_date) as tgl, count(*) as total')
            ->whereBetween('booking_date', [$start, $end])
            ->whereIn('status', self::BOOKING_ACTIVE_STATUSES)
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        // Agregat pesanan per tanggal pengerjaan (pesanans.booking_date ATAU form_pesanans.jadwal_pengerjaan)
        $pesananData = Pesanan::query()
            ->leftJoin('form_pesanans', 'pesanans.id_pesanan', '=', 'form_pesanans.id_pesanan')
            ->selectRaw('COALESCE(DATE(pesanans.booking_date), DATE(form_pesanans.jadwal_pengerjaan)) as tgl, count(*) as total')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('pesanans.booking_date', [$start, $end])
                  ->orWhereBetween('form_pesanans.jadwal_pengerjaan', [$start, $end]);
            })
            ->whereIn('pesanans.status', self::PESANAN_ACTIVE_STATUSES)
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();

        // Gabungkan semua tanggal unik
        $allDates = array_unique(array_merge(array_keys($bookingData), array_keys($pesananData)));

        $result = [];
        foreach ($allDates as $date) {
            if (!$date) continue;
            $bCount    = (int) ($bookingData[$date] ?? 0);
            $pCount    = (int) ($pesananData[$date] ?? 0);
            $totalUsed = $bCount + $pCount;

            $result[$date] = [
                'available'     => max(self::MAX_SLOT_PER_DAY - $totalUsed, 0),
                'is_full'       => $totalUsed >= self::MAX_SLOT_PER_DAY,
                'booked_count'  => $bCount,
                'pesanan_count' => $pCount,
                'total_used'    => $totalUsed,
                'max'           => self::MAX_SLOT_PER_DAY,
            ];
        }

        return $result;
    }

    /**
     * -------------------------------------------------------
     * Assert slot tersedia untuk tanggal tertentu.
     * Lempar exception jika sudah penuh.
     *
     * Digunakan oleh BookingService dan PesananService
     * sebelum menyimpan data ke database.
     * -------------------------------------------------------
     *
     * @throws \App\Exceptions\SlotPenuhException
     */
    public function assertSlotAvailable(string $date): void
    {
        // Gunakan DB::transaction + lockForUpdate agar tidak ada race condition
        // ketika dua request masuk bersamaan (anti-overbooking).
        $totalUsed = DB::table('bookings')
            ->whereDate('booking_date', $date)
            ->whereIn('status', self::BOOKING_ACTIVE_STATUSES)
            ->lockForUpdate()
            ->count();

        $totalUsed += DB::table('pesanans')
            ->leftJoin('form_pesanans', 'pesanans.id_pesanan', '=', 'form_pesanans.id_pesanan')
            ->where(function ($q) use ($date) {
                $q->whereDate('pesanans.booking_date', $date)
                  ->orWhereDate('form_pesanans.jadwal_pengerjaan', $date);
            })
            ->whereIn('pesanans.status', self::PESANAN_ACTIVE_STATUSES)
            ->lockForUpdate()
            ->count();

        if ($totalUsed >= self::MAX_SLOT_PER_DAY) {
            throw new \App\Exceptions\SlotPenuhException($date, $totalUsed, self::MAX_SLOT_PER_DAY);
        }
    }

    /**
     * Hapus cache kuota untuk tanggal tertentu.
     * Dipanggil setelah booking/pesanan dibuat atau dibatalkan.
     */
    public function clearCache(string $date): void
    {
        Cache::forget("slot_quota_{$date}");
    }
}
