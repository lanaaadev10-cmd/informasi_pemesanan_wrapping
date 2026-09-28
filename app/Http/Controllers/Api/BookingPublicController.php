<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pesanan;
use App\Services\BookingService;
use App\Services\SlotKuotaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Endpoint publik untuk informasi kuota booking (landing page).
 * Tanpa autentikasi, hanya read-only data kuota.
 */
class BookingPublicController extends Controller
{
    public function __construct(protected BookingService $bookingService) {}

    public function getTodayQuota(): JsonResponse
    {
        return response()->json([
            'date'  => now()->toDateString(),
            'quota' => $this->bookingService->getTodayQuota(),
        ]);
    }

    public function checkQuota(string $date): JsonResponse
    {
        if (!strtotime($date)) {
            return response()->json([
                'message'    => 'Format tanggal tidak valid. Gunakan Y-m-d.',
                'error_code' => 'INVALID_DATE',
            ], 422);
        }

        return response()->json([
            'date'  => $date,
            'quota' => $this->bookingService->checkQuota($date),
        ]);
    }

    public function getMonthQuota(int $year, int $month): JsonResponse
    {
        if ($month < 1 || $month > 12) {
            return response()->json([
                'message'    => 'Bulan harus antara 1-12.',
                'error_code' => 'INVALID_MONTH',
            ], 422);
        }

        return response()->json([
            'year'  => $year,
            'month' => $month,
            'quota' => $this->bookingService->getMonthQuota($year, $month),
        ]);
    }

    /**
     * Detail slot untuk satu hari — dipakai kalender dashboard.
     * Mengembalikan:
     *  - kuota (available, total_used, max, is_full)
     *  - list booking anonim (tanpa nama user) + flag is_mine jika login
     *  - list pesanan anonim di hari yang sama
     */
    public function getDayDetail(string $date): JsonResponse
    {
        if (!strtotime($date)) {
            return response()->json(['message' => 'Format tanggal tidak valid.'], 422);
        }

        $currentUserId = Auth::id(); // null jika belum login

        // ── Kuota gabungan ──────────────────────────────────────────
        $quota = $this->bookingService->checkQuota($date);

        // ── Booking aktif di hari itu ───────────────────────────────
        $bookings = Booking::with('layanan')
            ->whereDate('booking_date', $date)
            ->whereIn('status', SlotKuotaService::BOOKING_ACTIVE_STATUSES)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($b) => [
                'slot_type'    => 'booking',
                'layanan'      => $b->layanan?->nama_layanan ?? 'Layanan',
                'status'       => $b->status instanceof \App\Enums\BookingStatus
                                    ? $b->status->value
                                    : (string) $b->status,
                'status_label' => $b->status instanceof \App\Enums\BookingStatus
                                    ? $b->status->label()
                                    : ucfirst(str_replace('_', ' ', $b->status)),
                'submitted_at' => $b->created_at?->format('H:i') . ' WIB',
                'is_mine'      => $currentUserId && $b->user_id === $currentUserId,
                'show_url'     => ($currentUserId && $b->user_id === $currentUserId)
                                    ? route('booking.show', $b->id)
                                    : null,
            ]);

        // ── Pesanan aktif di hari yang sama ─────────────────────────
        $pesanans = Pesanan::with(['details.layanan', 'form'])
            ->where(function ($q) use ($date) {
                $q->whereDate('booking_date', $date)
                  ->orWhereHas('form', fn ($fq) => $fq->whereDate('jadwal_pengerjaan', $date));
            })
            ->whereIn('status', SlotKuotaService::PESANAN_ACTIVE_STATUSES)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($p) => [
                'slot_type'    => 'pesanan',
                'layanan'      => $p->details?->first()?->layanan?->nama_layanan ?? 'Pesanan',
                'status'       => $p->status,
                'status_label' => $p->label_status ?? ucfirst(str_replace('_', ' ', $p->status)),
                'submitted_at' => $p->created_at?->format('H:i') . ' WIB',
                'is_mine'      => $currentUserId && $p->id_user === $currentUserId,
                'show_url'     => ($currentUserId && $p->id_user === $currentUserId)
                                    ? route('pesanan.show', $p->id_pesanan)
                                    : null,
            ]);

        // Gabungkan dan urutkan by submitted_at
        $allSlots = $bookings->concat($pesanans)->sortBy('submitted_at')->values();

        return response()->json([
            'date'  => $date,
            'quota' => $quota,
            'slots' => $allSlots,
        ]);
    }
}