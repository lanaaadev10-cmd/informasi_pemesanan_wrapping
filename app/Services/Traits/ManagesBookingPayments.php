<?php

namespace App\Services\Traits;

use App\Enums\BookingStatus;
use App\Events\BookingPaymentUploaded;
use App\Events\BookingPaymentVerified;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Layanan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Trait untuk manajemen pembayaran, DP, dan verifikasi bukti transfer booking.
 */
trait ManagesBookingPayments
{
    /**
     * Hitung jumlah DP yang harus dibayar (50% dari harga paket).
     */
    public function calculateDpAmount(Layanan $layanan): float
    {
        return round(((float) $layanan->harga) * 0.5);
    }

    /**
     * User mengunggah bukti transfer.
     */
    public function uploadPaymentProof(Booking $booking, UploadedFile $file, string $paymentMethod = 'transfer_bank'): BookingPayment
    {
        if (!$booking->status->canUploadPayment()) {
            throw new \Exception("Booking tidak sedang menunggu pembayaran (status: {$booking->status->label()})");
        }

        $payment = $booking->payment;

        if (!$payment) {
            $payment = BookingPayment::create([
                'booking_id' => $booking->id,
                'payment_method' => $paymentMethod,
                'amount' => 0,
                'status' => 'pending',
            ]);
        }

        $path = $file->store('bukti_booking', 'public');

        DB::beginTransaction();
        try {
            $payment->update([
                'proof_file' => $path,
                'status' => 'pending',
            ]);

            $this->updateStatus($booking, BookingStatus::PAYMENT_UPLOADED);

            DB::commit();

            $payment->load('booking');
            event(new BookingPaymentUploaded($payment));

            return $payment;
        } catch (\Throwable $e) {
            DB::rollBack();
            Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    /**
     * Admin memverifikasi bukti bayar dengan pessimistic locking.
     */
    public function verifyPayment(BookingPayment $payment, ?string $notes = null): BookingPayment
    {
        DB::beginTransaction();
        try {
            $payment = BookingPayment::lockForUpdate()->findOrFail($payment->id);

            if (!$payment->isPending()) {
                throw new \Exception('Status pembayaran bukan pending');
            }

            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'admin_notes' => $notes,
            ]);

            $this->updateStatus($payment->booking, BookingStatus::APPROVED, $notes);

            DB::commit();

            $payment->load('booking.user', 'booking.layanan');
            event(new BookingPaymentVerified($payment->booking));

            return $payment->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Admin menolak bukti bayar: hapus file, balik ke AWAITING_PAYMENT.
     */
    public function rejectPayment(BookingPayment $payment, string $alasan): BookingPayment
    {
        if (!$payment->isPending()) {
            throw new \Exception('Status pembayaran bukan pending');
        }

        DB::beginTransaction();
        try {
            $this->deletePaymentProof($payment);

            $payment->update([
                'status' => 'rejected',
                'admin_notes' => $alasan,
            ]);

            $booking = $payment->booking;
            if ($booking->status === BookingStatus::PAYMENT_UPLOADED) {
                $this->updateStatus($booking, BookingStatus::AWAITING_PAYMENT, $alasan);
            }

            DB::commit();

            return $payment->fresh();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Hapus file bukti bayar.
     */
    public function deletePaymentProof(BookingPayment $payment): void
    {
        if ($payment->proof_file) {
            Storage::disk('public')->delete($payment->proof_file);
        }
        $payment->update(['proof_file' => null]);
    }
}
