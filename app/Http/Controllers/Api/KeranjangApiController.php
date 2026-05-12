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
     * Ambil isi keranjang aktif user (dengan cache 60 detik)
     */
    public function index()
    {
        $userId = Auth::id();
        $cacheKey = "keranjang_user_{$userId}";

        $keranjang = Cache::remember($cacheKey, 60, function () use ($userId) {
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
                    'nama_paket'     => $item->layanan->nama_paket ?? '-',
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
     */
    public function tambah(Request $request)
    {
        $request->validate([
            'id_paket'       => 'required|exists:layanans,id',
            'jumlah'         => 'required|integer|min:1',
            'catatan_custom' => 'nullable|string|max:500',
        ]);

        $userId  = Auth::id();
        $layanan = Layanan::findOrFail($request->id_paket);

        $keranjang = Keranjang::firstOrCreate(
            ['id_user' => $userId, 'status' => 'active']
        );

        $hargaSatuan = $layanan->harga_dasar;

        DetailKeranjang::create([
            'id_keranjang'   => $keranjang->id_keranjang,
            'id_paket'       => $request->id_paket,
            'jumlah'         => $request->jumlah,
            'catatan_custom' => $request->catatan_custom,
            'harga_satuan'   => $hargaSatuan,
            'subtotal'       => $hargaSatuan * $request->jumlah,
        ]);

        // Hapus cache agar data terbaru ditampilkan
        Cache::forget("keranjang_user_{$userId}");

        return response()->json([
            'status'  => 'ok',
            'message' => 'Paket berhasil ditambahkan ke keranjang.',
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

        // Pastikan item ini milik user yg login
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
}
