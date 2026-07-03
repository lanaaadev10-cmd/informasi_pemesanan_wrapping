<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PesananResource;
use App\Http\Requests\Admin\Pesanan\UpdateOrderStatusRequest;
use App\Services\PesananService;
use App\Models\Pesanan;
use Illuminate\Http\Request;

/**
 * Admin Pesanan (Orders) Controller
 * 
 * Admin-only endpoints for order management
 */
class AdminPesananController extends Controller
{
    public function __construct(
        protected PesananService $pesananService,
    ) {}

    /**
     * GET /api/admin/pesanan
     * List all orders (admin view)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $status = $request->get('status');
        $userId = $request->get('user_id');

        $query = Pesanan::with(['details.layanan', 'form', 'pembayaran', 'user']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('id_user', $userId);
        }

        $pesanans = $query->orderByDesc('tanggal_pesan')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pesanan berhasil diambil',
            'data' => PesananResource::collection($pesanans->items()),
            'pagination' => [
                'current_page' => $pesanans->currentPage(),
                'total' => $pesanans->total(),
                'per_page' => $pesanans->perPage(),
                'last_page' => $pesanans->lastPage(),
            ],
        ], 200);
    }

    /**
     * GET /api/admin/pesanan/:id
     * Get order detail (admin view)
     */
    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['details.layanan', 'form', 'pembayaran', 'notifikasis', 'user']);

        return response()->json([
            'status' => 'success',
            'message' => 'Detail pesanan berhasil diambil',
            'data' => new PesananResource($pesanan),
        ], 200);
    }

    /**
     * PUT /api/admin/pesanan/:id/status
     * Update order status
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Pesanan $pesanan)
    {
        try {
            $pesanan = $this->pesananService->updateStatus(
                $pesanan,
                $request->status,
                $request->catatan_admin,
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Status pesanan berhasil diupdate',
                'data' => new PesananResource($pesanan->fresh()->load(['details', 'form', 'pembayaran'])),
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
     * POST /api/admin/pesanan/:id/note
     * Add admin note to order
     */
    public function addNote(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000',
        ]);

        $pesanan->update(['catatan_admin' => $request->catatan_admin]);

        return response()->json([
            'status' => 'success',
            'message' => 'Catatan admin berhasil ditambahkan',
            'data' => new PesananResource($pesanan->fresh()),
        ], 200);
    }
}
