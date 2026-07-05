<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class KeranjangApiController extends Controller
{
    /**
     * GET /api/keranjang
     * Ambil isi keranjang aktif user (dengan cache)
     */
    public function index()
    {
        $userId = Auth::id();
        $cacheKey = "keranjang_user_{$userId}";
        $cacheDuration = config('app-settings.cache.api_keranjang', 60);

        $keranjang = Cache::remember($cacheKey, $cacheDuration, function () use ($userId) {
            return Keranjang::where('id_user', $userId)
                ->where('status', 'active')
                ->with('details.layanan')
                ->first();
        });

        if (!$keranjang) {
            return response()->json([
                'status'  => 'empty',
                'message' => 'Keranjang kosong.',
                'data'    => [],
                'total'   => 0,
            ]);
        }

        $total = $keranjang->details->sum('subtotal');

        return response()->json([
            'status'  => 'ok',
            'data'    => $keranjang->details->map(function ($item) {
                return [
                    'id_detail'      => $item->id_detail,
                    'nama_layanan'   => $item->layanan->nama_layanan ?? '-',
                    'jumlah'         => $item->jumlah,
                    'harga_satuan'   => $item->harga_satuan,
                    'subtotal'       => $item->subtotal,
                    'catatan_custom' => $item->catatan_custom,
                ];
            }),
            'total'   => $total,
        ]);
    }

    /**
     * POST /api/keranjang/tambah
     * Tambah item ke keranjang + bersihkan cache
     * Max 3 paket per user
     */
    public function tambah(Request $request)
    {
        $request->validate([
            'id_paket'       => 'required|exists:layanans,id_layanan',
            'jumlah'         => 'required|integer|min:1',
            'catatan_custom' => 'nullable|string|max:500',
        ]);

        $userId  = Auth::id();
        $layanan = Layanan::findOrFail($request->id_paket);

        $keranjang = Keranjang::firstOrCreate(
            ['id_user' => $userId, 'status' => 'active']
        );

        // Check max 3 paket unique
        $itemCount = $keranjang->details()->count();

        // Check if paket already exists in cart
        $existingItem = $keranjang->details()
            ->where('id_paket', $request->id_paket)
            ->first();

        if (!$existingItem && $itemCount >= 3) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Maksimal hanya 3 paket dalam keranjang.',
                'max_items' => 3,
                'current_items' => $itemCount,
            ], 422);
        }

        $hargaSatuan = $layanan->harga;

        if ($existingItem) {
            // Update existing item quantity
            $existingItem->update([
                'jumlah' => $existingItem->jumlah + $request->jumlah,
                'subtotal' => ($existingItem->jumlah + $request->jumlah) * $hargaSatuan,
            ]);
        } else {
            // Create new item
            DetailKeranjang::create([
                'id_keranjang'   => $keranjang->id_keranjang,
                'id_paket'       => $request->id_paket,
                'jumlah'         => $request->jumlah,
                'catatan_custom' => $request->catatan_custom,
                'harga_satuan'   => $hargaSatuan,
                'subtotal'       => $hargaSatuan * $request->jumlah,
            ]);
        }

        Cache::forget("keranjang_user_{$userId}");

        return response()->json([
            'status'  => 'ok',
            'message' => 'Paket berhasil ditambahkan ke keranjang.',
            'cart_count' => $keranjang->details()->count(),
        ], 201);
    }

    /**
     * DELETE /api/keranjang/{id_detail}
     * Hapus item dari keranjang + bersihkan cache
     */
    public function hapus($id_detail)
    {
        $userId = Auth::id();
        $detail = DetailKeranjang::findOrFail($id_detail);

        if ($detail->keranjang->id_user !== $userId) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $detail->delete();

        Cache::forget("keranjang_user_{$userId}");

        return response()->json([
            'status'  => 'ok',
            'message' => 'Item berhasil dihapus dari keranjang.',
        ]);
    }

    /**
     * PATCH /api/keranjang/{id}
     * Update quantity for cart item
     */
    public function update(Request $request, $id_detail)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $detail = DetailKeranjang::findOrFail($id_detail);

        if ($detail->keranjang->id_user !== $userId) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $hargaSatuan = $detail->harga_satuan;
        $subtotal = $request->jumlah * $hargaSatuan;

        $detail->update([
            'jumlah' => $request->jumlah,
            'subtotal' => $subtotal
        ]);

        $keranjang = Keranjang::where('id_user', $userId)
            ->where('status', 'active')
            ->with('details')
            ->first();

        $total = $keranjang->details->sum('subtotal');

        Cache::forget("keranjang_user_{$userId}");

        return response()->json([
            'status' => 'ok',
            'message' => 'Jumlah berhasil diperbarui.',
            'data' => [
                'id_detail' => $id_detail,
                'jumlah' => $request->jumlah,
                'subtotal' => $subtotal,
                'total' => $total,
            ]
        ]);
    }

    /**
     * DELETE /api/keranjang
     * Empty entire cart
     */
    public function kosongkan()
    {
        $userId = Auth::id();

        $keranjang = Keranjang::where('id_user', $userId)
            ->where('status', 'active')
            ->first();

        if ($keranjang) {
            $keranjang->details()->delete();
        }

        Cache::forget("keranjang_user_{$userId}");

        return response()->json([
            'status' => 'ok',
            'message' => 'Keranjang berhasil dikosongkan.',
        ]);
    }

    /**
     * GET /api/keranjang/check/{id_paket}
     * Check if specific paket is in active cart
     */
    public function checkInCart($id_paket)
    {
        $userId = Auth::id();

        $keranjang = Keranjang::where('id_user', $userId)
            ->where('status', 'active')
            ->first();

        if (!$keranjang) {
            return response()->json([
                'status' => 'not_found',
                'in_cart' => false,
            ]);
        }

        $inCart = $keranjang->details()
            ->where('id_paket', $id_paket)
            ->exists();

        return response()->json([
            'status' => 'ok',
            'in_cart' => $inCart,
            'cart_count' => $keranjang->details()->count(),
            'can_add' => $keranjang->details()->count() < 3,
        ]);
    }

    /**
     * GET /api/keranjang/count
     * Get current cart item count
     */
    public function getCartCount()
    {
        $userId = Auth::id();

        $keranjang = Keranjang::where('id_user', $userId)
            ->where('status', 'active')
            ->first();

        $count = $keranjang ? $keranjang->details()->count() : 0;

        return response()->json([
            'status' => 'ok',
            'count' => $count,
        ]);
    }
}
