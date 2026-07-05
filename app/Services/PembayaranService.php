<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
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
    public function __construct(
        protected PesananService $pesananService,
    ) {}
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
                'jumlah_bayar' => $pesanan->total_harga,
                'status' => PaymentStatus::PENDING->value,
            ]);
        }

        // Store file first (file system has no transaction rollback)
        $path = $file->store(
            'bukti_transfer',
            'public'
        );

        DB::beginTransaction();
        try {
            // Update payment record with file path
            $pembayaran->update([
                'bukti_transfer' => $path,
                'status' => PaymentStatus::PENDING->value,
            ]);

            DB::commit();

            // Emit event with loaded relations
            $pembayaran->load('pesanan');
            event(new PaymentUploaded($pembayaran));

            return $pembayaran;
        } catch (\Exception $e) {
            DB::rollBack();
            // Clean up file if DB commit fails
            Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    /**
     * Verify payment oleh admin dengan pessimistic locking
     */
    public function verifyPayment(Pembayaran $pembayaran, ?string $catatan = null): Pembayaran
    {
        DB::beginTransaction();
        try {
            // Lock payment record to prevent concurrent verification
            $pembayaran = Pembayaran::lockForUpdate()->find($pembayaran->id_pembayaran);

            if ($pembayaran->status !== PaymentStatus::PENDING->value) {
                throw new \Exception('Payment tidak dalam status pending');
            }

            $pembayaran->update([
                'status' => PaymentStatus::VERIFIED->value,
                'tgl_bayar' => now(),
            ]);

            // Update pesanan status via PesananService (state machine validation)
            // Step 1: Konfirmasi pembayaran (menunggu_verifikasi_pembayaran -> dikonfirmasi)
            $pesanan = $pembayaran->pesanan;
            $this->pesananService->updateStatus(
                $pesanan,
                OrderStatus::DIKONFIRMASI->value,
                $catatan
            );

            // Step 2: Mulai proses (dikonfirmasi -> sedang_diproses)
            $this->pesananService->updateStatus(
                $pesanan->fresh(),
                OrderStatus::SEDANG_DIPROSES->value,
                $catatan
            );

            DB::commit();

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
        if ($pembayaran->status !== PaymentStatus::PENDING->value) {
            throw new \Exception('Payment tidak dalam status pending');
        }

        DB::beginTransaction();
        try {
            // Hapus bukti transfer jika ada
            $this->deletePaymentProof($pembayaran);

            $pembayaran->update([
                'status' => PaymentStatus::REJECTED->value,
            ]);

            // Update pesanan status via PesananService (state machine validation)
            $pesanan = $pembayaran->pesanan;
            $this->pesananService->updateStatus(
                $pesanan,
                OrderStatus::MENUNGGU_PEMBAYARAN->value,
                $alasan
            );

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
