<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PembayaranResource;
use App\Services\PembayaranService;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

/**
 * Admin Pembayaran (Payment) Controller
 * 
 * Admin-only endpoints for payment verification
 */
class AdminPembayaranController extends Controller
{
    public function __construct(
        protected PembayaranService $pembayaranService,
    ) {}

    /**
     * GET /api/admin/pembayaran
     * List all pending payments for verification
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $status = $request->get('status', 'pending');

        $pembayarans = Pembayaran::with(['pesanan.user', 'pesanan.details'])
            ->where('status_pembayaran', $status)
            ->orderByDesc('updated_at')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pembayaran berhasil diambil',
            'data' => PembayaranResource::collection($pembayarans->items()),
            'pagination' => [
                'current_page' => $pembayarans->currentPage(),
                'total' => $pembayarans->total(),
                'per_page' => $pembayarans->perPage(),
                'last_page' => $pembayarans->lastPage(),
            ],
        ], 200);
    }

    /**
     * PUT /api/admin/pembayaran/:id/verify
     * Verify payment
     */
    public function verify(Pembayaran $pembayaran)
    {
        try {
            $pembayaran = $this->pembayaranService->verifyPayment($pembayaran);

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi',
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

    /**
     * PUT /api/admin/pembayaran/:id/reject
     * Reject payment
     */
    public function reject(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        try {
            $pembayaran = $this->pembayaranService->rejectPayment(
                $pembayaran,
                $request->alasan,
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil ditolak',
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
