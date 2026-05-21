<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Enums\PaymentStatus;
use App\Events\PaymentUploaded;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * Service untuk manage payment operations
 * 
 * Handles:
 * - Upload payment proof
 * - Verify payment
 * - Reject payment
 * - Get payment info
 */
class PembayaranService
{
    /**
     * Process payment upload
     */
    public function uploadPaymentProof(Pesanan $pesanan, $file): Pembayaran
    {
        // Validasi order status
        if ($pesanan->status !== \App\Enums\OrderStatus::MENUNGGU_PEMBAYARAN->value) {
            throw new \Exception('Order tidak sedang menunggu pembayaran');
        }

        // Check if payment record exists
        $pembayaran = $pesanan->pembayaran;
        if (!$pembayaran) {
            // Get metode pembayaran dari form
            $metodeFromForm = $pesanan->form?->metode_pembayaran ?? 'transfer_bank';

            // Create payment record
            $pembayaran = Pembayaran::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'metode_pembayaran' => $metodeFromForm,
                'nomor_referensi' => 'REF-' . Str::random(12),
                'jumlah_bayar' => $pesanan->total_harga,
                'status_pembayaran' => PaymentStatus::PENDING->value,
            ]);
        }

        DB::beginTransaction();
        try {
            // Store file
            $path = $file->storeAs(
                'payments/' . $pesanan->id_pesanan,
                'proof_' . time() . '.' . $file->extension(),
                'public'
            );

            // Update payment record
            $pembayaran->update([
                'bukti_transfer' => $path,
                'status_pembayaran' => PaymentStatus::PENDING->value,
            ]);

            DB::commit();

            // Emit event
            event(new PaymentUploaded($pembayaran->fresh()->load('pesanan')));

            return $pembayaran->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify payment oleh admin
     */
    public function verifyPayment(Pembayaran $pembayaran, ?string $catatan = null): Pembayaran
    {
        if ($pembayaran->status_pembayaran !== PaymentStatus::PENDING->value) {
            throw new \Exception('Payment tidak dalam status pending');
        }

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'status_pembayaran' => PaymentStatus::VERIFIED->value,
                'tanggal_pembayaran' => now(),
            ]);

            // Update pesanan status to sedang diproses
            $pesanan = $pembayaran->pesanan;
            $pesanan->update(['status' => \App\Enums\OrderStatus::SEDANG_DIPROSES->value]);

            DB::commit();

            // Emit events
            event(new \App\Events\PaymentVerified($pesanan->fresh()->load(['details', 'form'])));

            return $pembayaran->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject payment oleh admin
     */
    public function rejectPayment(Pembayaran $pembayaran, string $alasan): Pembayaran
    {
        if ($pembayaran->status_pembayaran !== PaymentStatus::PENDING->value) {
            throw new \Exception('Payment tidak dalam status pending');
        }

        DB::beginTransaction();
        try {
            $pembayaran->update([
                'status_pembayaran' => PaymentStatus::REJECTED->value,
            ]);

            // Update pesanan status kembali ke menunggu pembayaran
            $pesanan = $pembayaran->pesanan;
            $pesanan->update([
                'status' => \App\Enums\OrderStatus::MENUNGGU_PEMBAYARAN->value,
                'catatan_admin' => $alasan,
            ]);

            DB::commit();

            return $pembayaran->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get payment info
     */
    public function getPaymentInfo(Pesanan $pesanan): ?Pembayaran
    {
        return $pesanan->pembayaran;
    }

    /**
     * Delete payment proof file
     */
    public function deletePaymentProof(Pembayaran $pembayaran): void
    {
        if ($pembayaran->bukti_transfer) {
            Storage::disk('public')->delete($pembayaran->bukti_transfer);
        }

        $pembayaran->update(['bukti_transfer' => null]);
    }
}
