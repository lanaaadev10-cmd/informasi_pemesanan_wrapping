<?php

namespace App\Services\Traits;

use App\Enums\BookingStatus;
use App\Events\BookingCompleted;
use App\Events\BookingConfirmed;
use App\Events\BookingRejected;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

/**
 * Trait untuk siklus hidup status booking: Reschedule, Konfirmasi, Proses, Selesai, Tolak, dan Batal.
 */
trait ManagesBookingLifecycle
{
    /**
     * Admin mengubah jadwal booking (Reschedule).
     */
    public function rescheduleBooking(
        Booking $booking,
        string $newDate,
        ?string $newTime = null,
        ?string $adminNotes = null,
        bool $overrideQuota = false
    ): Booking {
        $oldDate = $booking->booking_date ? $booking->booking_date->toDateString() : null;

        if ($oldDate !== $newDate && !$overrideQuota) {
            $this->slotKuotaService->assertSlotAvailable($newDate);
        }

        DB::beginTransaction();
        try {
            $notes = $booking->admin_notes ? $booking->admin_notes . "\n" : '';
            $notes .= "[Reschedule " . now()->format('d/m/Y H:i') . "] Dari {$oldDate} ke {$newDate}";
            if ($adminNotes) {
                $notes .= ": {$adminNotes}";
            }

            $booking->update([
                'booking_date' => $newDate,
                'booking_time' => $newTime ?: $booking->booking_time,
                'admin_notes'  => $notes,
            ]);

            DB::commit();

            if ($oldDate) {
                $this->slotKuotaService->clearCache($oldDate);
            }
            $this->slotKuotaService->clearCache($newDate);

            return $booking->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Konfirmasi booking (PENDING -> CONFIRMED -> AWAITING_PAYMENT / PAYMENT_UPLOADED).
     */
    public function confirmBooking(Booking $booking, ?string $notes = null): Booking
    {
        $confirmed = $this->updateStatus($booking, BookingStatus::CONFIRMED, $notes);
        $awaiting = $this->updateStatus($confirmed, BookingStatus::AWAITING_PAYMENT, $notes);

        if ($awaiting->payment && $awaiting->payment->proof_file) {
            return $this->updateStatus($awaiting, BookingStatus::PAYMENT_UPLOADED, $notes);
        }

        return $awaiting;
    }

    /**
     * Admin menolak booking.
     */
    public function rejectBooking(Booking $booking, string $alasan): Booking
    {
        return $this->updateStatus($booking, BookingStatus::REJECTED, $alasan);
    }

    /**
     * Admin mulai pengerjaan workshop.
     */
    public function startProcessing(Booking $booking, ?string $notes = null): Booking
    {
        return $this->updateStatus($booking, BookingStatus::IN_PROGRESS, $notes);
    }

    /**
     * Admin selesaikan pengerjaan.
     */
    public function completeBooking(Booking $booking): Booking
    {
        return $this->updateStatus($booking, BookingStatus::COMPLETED);
    }

    /**
     * Pembatalan booking oleh user.
     */
    public function cancelBooking(Booking $booking, ?string $alasan = null): Booking
    {
        if (!$booking->bisaDibatalkan()) {
            throw new \Exception("Booking tidak dapat dibatalkan pada status {$booking->status->label()}");
        }

        $result = $this->updateStatus($booking, BookingStatus::CANCELLED, $alasan ?? 'Dibatalkan oleh customer');
        $this->slotKuotaService->clearCache($booking->booking_date->toDateString());

        return $result;
    }

    /**
     * Update status dengan validasi state machine.
     */
    public function updateStatus(Booking $booking, BookingStatus $newStatus, ?string $notes = null): Booking
    {
        $currentStatus = $booking->status;

        if (!in_array($newStatus, $currentStatus->validTransitions(), true)) {
            throw new \Exception("Transisi tidak diizinkan dari {$currentStatus->label()} ke {$newStatus->label()}");
        }

        DB::beginTransaction();
        try {
            $attributes = ['status' => $newStatus->value];
            if ($notes !== null) {
                $attributes['admin_notes'] = $notes;
            }
            $booking->update($attributes);

            DB::commit();
            $this->emitStatusChangeEvent($booking, $newStatus);

            return $booking->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Emit event status change.
     */
    protected function emitStatusChangeEvent(Booking $booking, BookingStatus $status): void
    {
        $b = $booking->load(['user', 'layanan', 'payment']);

        match ($status) {
            BookingStatus::CONFIRMED => event(new BookingConfirmed($b)),
            BookingStatus::APPROVED  => null,
            BookingStatus::COMPLETED => event(new BookingCompleted($b)),
            BookingStatus::REJECTED  => event(new BookingRejected($b)),
            BookingStatus::CANCELLED => event(new BookingRejected($b, alasan: 'Dibatalkan')),
            default                  => null,
        };
    }
}
