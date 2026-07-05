<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LayananApiController extends Controller
{
    /**
     * GET /api/layanans
     * List all services with pagination and filters
     */
    public function index(Request $request)
    {
        $query = Layanan::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tipe_layanan')) {
            $query->where('tipe_layanan', $request->tipe_layanan);
        }

        if ($request->filled('tipe_paket')) {
            $query->where('tipe_paket', $request->tipe_paket);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 20);
        $layanans = $query->paginate($perPage);

        return response()->json([
            'status' => 'ok',
            'data' => $layanans->items(),
            'pagination' => [
                'current_page' => $layanans->currentPage(),
                'total' => $layanans->total(),
                'per_page' => $layanans->perPage(),
                'last_page' => $layanans->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/layanans/{id}
     * Get single service details with caching
     */
    public function show($id)
    {
        $cacheKey = "layanan_{$id}";
        $cacheDuration = config('app-settings.cache.api_layanan', 3600);

        $layanan = Cache::remember($cacheKey, $cacheDuration, function () use ($id) {
            return Layanan::findOrFail($id, ['id_layanan', 'nama_layanan', 'deskripsi', 'foto_contoh', 'harga', 'tipe_layanan', 'tipe_paket', 'fitur', 'kategori']);
        });

        return response()->json([
            'status' => 'ok',
            'data' => $layanan,
        ]);
    }

    /**
     * GET /api/layanans/kategori/{kategori}
     * Filter services by category
     */
    public function byCategory($kategori, Request $request)
    {
        $perPage = $request->get('per_page', 20);

        $layanans = Layanan::where('kategori', $kategori)
            ->paginate($perPage);

        if ($layanans->isEmpty()) {
            return response()->json([
                'status' => 'empty',
                'message' => "Tidak ada layanan dengan kategori '{$kategori}'.",
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'data' => $layanans->items(),
            'pagination' => [
                'current_page' => $layanans->currentPage(),
                'total' => $layanans->total(),
                'per_page' => $layanans->perPage(),
                'last_page' => $layanans->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/layanans/categories
     * Get all available categories with caching
     */
    public function categories()
    {
        $cacheDuration = config('app-settings.cache.api_layanan', 3600);

        $categories = Cache::remember('layanan_categories', $cacheDuration, function () {
            return Layanan::distinct('kategori')
                ->pluck('kategori')
                ->filter()
                ->values();
        });

        return response()->json([
            'status' => 'ok',
            'data' => $categories,
        ]);
    }
}
