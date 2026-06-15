<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PembayaranResource;
use App\Http\Requests\Pembayaran\UploadPaymentProofRequest;
use App\Services\PembayaranService;
use App\Models\Pesanan;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Pembayaran (Payment) Controller
 * 
 * Endpoints:
 * - POST /api/pesanan/:id/pembayaran/upload (upload payment proof)
 * - GET /api/pesanan/:id/pembayaran/status (check payment status)
 */
class PembayaranController extends Controller
{
    public function __construct(
        protected PembayaranService $pembayaranService,
    ) {}

    /**
     * POST /api/pesanan/:pesananId/pembayaran/upload
     * Upload payment proof
     */
    public function upload(UploadPaymentProofRequest $request, $pesananId)
    {
        try {
            $pesanan = Pesanan::findOrFail($pesananId);

            // Authorization
            $this->authorize('update', $pesanan);

            $pembayaran = $this->pembayaranService->uploadPaymentProof(
                $pesanan,
                $request->file('bukti_transfer'),
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Bukti transfer berhasil diunggah. Menunggu verifikasi admin.',
                'data' => new PembayaranResource($pembayaran),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk mengupload pembayaran ini',
                'data' => null,
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }

    /**
     * GET /api/pesanan/:pesananId/pembayaran/status
     * Check payment status
     */
    public function getStatus($pesananId)
    {
        try {
            $pesanan = Pesanan::findOrFail($pesananId);

            // Authorization
            $this->authorize('view', $pesanan);

            $pembayaran = $this->pembayaranService->getPaymentInfo($pesanan);

            if (!$pembayaran) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Belum ada pembayaran untuk pesanan ini',
                    'data' => null,
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Status pembayaran berhasil diambil',
                'data' => new PembayaranResource($pembayaran),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
