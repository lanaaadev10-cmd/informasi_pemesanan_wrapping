<?php

namespace App\Services\Traits;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait untuk query, pencarian, dan kalkulasi statistik booking.
 */
trait ManagesBookingQueries
{
    /**
     * Detail booking dengan relasi lengkap.
     */
    public function getBookingDetails(int $id): Booking
    {
        return Booking::with(['user', 'layanan', 'payment'])->findOrFail($id);
    }

    /**
     * Daftar booking milik user dengan pagination & tab filter.
     */
    public function getUserBookings(int $userId, int $perPage = 10, ?string $status = null, ?string $tab = null): LengthAwarePaginator
    {
        $query = Booking::with(['layanan', 'payment'])->where('user_id', $userId);
        $filter = $tab ?: $status;

        if ($filter && $filter !== 'all') {
            match ($filter) {
                'unpaid', 'menunggu_bayar', 'awaiting_payment' => $query->where('status', BookingStatus::AWAITING_PAYMENT->value),
                'processing', 'diproses', 'active', 'berjalan' => $query->whereIn('status', [
                    BookingStatus::PENDING->value,
                    BookingStatus::CONFIRMED->value,
                    BookingStatus::PAYMENT_UPLOADED->value,
                    BookingStatus::APPROVED->value,
                    BookingStatus::IN_PROGRESS->value,
                ]),
                'completed', 'selesai' => $query->where('status', BookingStatus::COMPLETED->value),
                'cancelled', 'dibatalkan', 'rejected', 'ditolak', 'batal' => $query->whereIn('status', [
                    BookingStatus::CANCELLED->value,
                    BookingStatus::REJECTED->value,
                ]),
                default => $query->where('status', $filter),
            };
        }

        return $query->orderByDesc('booking_date')->paginate($perPage)->withQueryString();
    }

    /**
     * Statistik ringkas booking milik user.
     */
    public function getUserBookingStats(int $userId): array
    {
        $counts = Booking::query()
            ->where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $sum = fn (array $statuses): int => collect($statuses)->sum(fn (string $s): int => (int) ($counts[$s] ?? 0));

        $unpaidCount = (int) ($counts[BookingStatus::AWAITING_PAYMENT->value] ?? 0);
        $processingCount = $sum([
            BookingStatus::PENDING->value,
            BookingStatus::CONFIRMED->value,
            BookingStatus::PAYMENT_UPLOADED->value,
            BookingStatus::APPROVED->value,
            BookingStatus::IN_PROGRESS->value,
        ]);
        $completedCount = (int) ($counts[BookingStatus::COMPLETED->value] ?? 0);
        $cancelledCount = $sum([BookingStatus::CANCELLED->value, BookingStatus::REJECTED->value]);

        return [
            'total'          => $counts->sum(),
            'pending'        => (int) ($counts[BookingStatus::PENDING->value] ?? 0),
            'menunggu_bayar' => $sum([BookingStatus::AWAITING_PAYMENT->value, BookingStatus::PAYMENT_UPLOADED->value]),
            'aktif'          => $processingCount,
            'selesai'        => $completedCount,
            'tab_all'        => $counts->sum(),
            'tab_unpaid'     => $unpaidCount,
            'tab_processing' => $processingCount,
            'tab_completed'  => $completedCount,
            'tab_cancelled'  => $cancelledCount,
        ];
    }

    /**
     * Daftar semua booking (admin / filament).
     */
    public function getAllBookings(int $perPage = 15, ?string $status = null, ?string $date = null): LengthAwarePaginator
    {
        $query = Booking::with(['user', 'layanan', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }
        if ($date) {
            $query->where('booking_date', $date);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }
}
