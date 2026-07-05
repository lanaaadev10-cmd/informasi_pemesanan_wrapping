<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PesananResource;
use App\Http\Requests\Pesanan\CheckoutRequest;
use App\Services\PesananService;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Pesanan (Orders) Controller
 * 
 * Endpoints:
 * - GET /api/pesanan (list user's orders)
 * - POST /api/pesanan (create order - checkout)
 * - GET /api/pesanan/:id (get order detail)
 * - PUT /api/pesanan/:id/cancel (cancel order)
 * - GET /api/pesanan/:id/invoice (get invoice)
 */
class PesananController extends Controller
{
    public function __construct(
        protected PesananService $pesananService,
    ) {}

    /**
     * GET /api/pesanan
     * List user's orders with pagination
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status');

        $pesanans = $this->pesananService->getUserOrders($userId, $perPage, $status);

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
     * POST /api/pesanan
     * Create new order (checkout from cart)
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $userId = auth()->id();
            
            $pesanan = $this->pesananService->checkout($userId, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibuat',
                'data' => new PesananResource($pesanan),
            ], 201);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat pesanan. Silakan periksa kembali data Anda.',
                'data' => null,
            ], 400);
        }
    }

    /**
     * GET /api/pesanan/:id
     * Get order detail
     */
    public function show($id)
    {
        try {
            $pesanan = $this->pesananService->getOrderDetails($id);

            $this->authorize('view', $pesanan);

            return response()->json([
                'status' => 'success',
                'message' => 'Detail pesanan berhasil diambil',
                'data' => new PesananResource($pesanan),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke pesanan ini',
                'data' => null,
            ], 403);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Detail pesanan tidak ditemukan.',
                'data' => null,
            ], 404);
        }
    }

    /**
     * PUT /api/pesanan/:id/cancel
     * Cancel order (only if allowed)
     */
    public function cancel($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            $this->authorize('update', $pesanan);

            $pesanan = $this->pesananService->cancelOrder($pesanan);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dibatalkan',
                'data' => new PesananResource($pesanan),
            ], 200);
        } catch (AuthorizationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk membatalkan pesanan ini',
                'data' => null,
            ], 403);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak dapat dibatalkan. Silakan hubungi admin.',
                'data' => null,
            ], 400);
        }
    }

    /**
     * GET /api/pesanan/:id/invoice
     * Get order invoice (PDF)
     */
    public function getInvoice($id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            $this->authorize('view', $pesanan);

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice berhasil diambil',
                'data' => [
                    'kode_pesanan' => $pesanan->kode_pesanan,
                    'tanggal_pesan' => $pesanan->tanggal_pesan,
                    'total_harga' => $pesanan->total_harga,
                    'details' => $pesanan->details,
                    'form' => $pesanan->form,
                ],
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil invoice. Silakan coba lagi.',
                'data' => null,
            ], 400);
        }
    }
}
