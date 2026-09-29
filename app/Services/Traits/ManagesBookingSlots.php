<?php

namespace App\Services\Traits;

use App\Exceptions\Booking\BookingDuplicateException;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\User;

/**
 * Trait untuk manajemen kuota, ketersediaan slot, dan guard booking.
 */
trait ManagesBookingSlots
{
    /**
     * Cek kuota suatu tanggal.
     * Didelegasikan ke SlotKuotaService agar kuota dihitung bersama
     * antara Booking dan Pesanan (shared 5 slot/hari).
     *
     * @return array{available: int, is_full: bool, booked_count: int, pesanan_count: int, total_used: int, max: int}
     */
    public function checkQuota(string $date): array
    {
        return $this->slotKuotaService->checkQuota($date);
    }

    /**
     * Status kuota hari ini (untuk widget landing page).
     */
    public function getTodayQuota(): array
    {
        return $this->slotKuotaService->getTodayQuota();
    }

    /**
     * Kuota per tanggal dalam satu bulan untuk kalender landing page.
     *
     * @return array<string, array>
     */
    public function getMonthQuota(int $year, int $month): array
    {
        return $this->slotKuotaService->getMonthQuota($year, $month);
    }

    /**
     * Ambil daftar layanan aktif untuk form booking.
     */
    public function getAvailableLayanans()
    {
        return Layanan::query()
            ->whereIn('tipe_layanan', ['fix', 'custom'])
            ->orderBy('nama_layanan')
            ->get();
    }

    /**
     * Guard bisnis: satu user hanya boleh punya 1 booking aktif per tanggal
     * (mencegah double-submit / memborong slot). Null-kan excludeBookingId
     * saat dipakai untuk membuat baru.
     */
    public function assertSlotAvailableForUser(User $user, string $bookingDate, ?int $excludeBookingId = null): void
    {
        $alreadyBooked = Booking::query()
            ->where('user_id', $user->id)
            ->whereDate('booking_date', $bookingDate)
            ->whereIn('status', [
                'pending',
                'confirmed',
                'awaiting_payment',
                'payment_uploaded',
                'approved',
                'in_progress',
            ])
            ->when($excludeBookingId, fn ($query) => $query->where('id', '!=', $excludeBookingId))
            ->exists();

        if ($alreadyBooked) {
            throw new BookingDuplicateException($bookingDate);
        }
    }
}
