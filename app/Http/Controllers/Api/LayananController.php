<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LayananResource;
use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * Layanan (Services/Catalog) Controller
 * 
 * Endpoints:
 * - GET /api/layanan (list all services)
 * - GET /api/layanan/:id (get service detail)
 * - GET /api/layanan/kategori/:slug (filter by category)
 */
class LayananController extends Controller
{
    /**
     * GET /api/layanan
     * List all services with optional filters & pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 12);
        $kategori = $request->get('kategori');
        $tipeLayanan = $request->get('tipe_layanan');
        $tipePacket = $request->get('tipe_paket');

        $query = Layanan::where('is_active', true);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($tipeLayanan) {
            $query->where('tipe_layanan', $tipeLayanan);
        }

        if ($tipePacket) {
            $query->where('tipe_paket', $tipePacket);
        }

        $layanans = $query->orderBy('nama_layanan')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'message' => 'Data layanan berhasil diambil',
            'data' => LayananResource::collection($layanans->items()),
            'pagination' => [
                'current_page' => $layanans->currentPage(),
                'total' => $layanans->total(),
                'per_page' => $layanans->perPage(),
                'last_page' => $layanans->lastPage(),
            ],
        ], 200);
    }

    /**
     * GET /api/layanan/:id
     * Get service detail
     */
    public function show(Layanan $layanan)
    {
        // Authorization: service harus active atau user adalah admin
        if (!$layanan->is_active && !auth()?->user()?->hasRole('admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Layanan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail layanan berhasil diambil',
            'data' => new LayananResource($layanan),
        ], 200);
    }

    /**
     * GET /api/layanan/kategori/:kategori
     * Filter services by category
     */
    public function filterByCategory(Request $request, $kategori)
    {
        $perPage = $request->get('per_page', 12);

        $layanans = Layanan::where('is_active', true)
            ->where('kategori', $kategori)
            ->orderBy('nama_layanan')
            ->paginate($perPage);

        if ($layanans->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tidak ada layanan untuk kategori ini',
                'data' => [],
                'pagination' => [
                    'current_page' => 1,
                    'total' => 0,
                    'per_page' => $perPage,
                    'last_page' => 0,
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data layanan kategori berhasil diambil',
            'data' => LayananResource::collection($layanans->items()),
            'pagination' => [
                'current_page' => $layanans->currentPage(),
                'total' => $layanans->total(),
                'per_page' => $layanans->perPage(),
                'last_page' => $layanans->lastPage(),
            ],
        ], 200);
    }
}
