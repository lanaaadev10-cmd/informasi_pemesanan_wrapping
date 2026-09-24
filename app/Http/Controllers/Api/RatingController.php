<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TestimoniController;
use App\Models\Layanan;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function __construct(
        protected RatingService $ratingService,
    ) {}

    /**
     * GET /api/rating
     * Daftar ulasan publik (is_tampil = true) + ringkasan rata-rata.
     */
    public function index(Request $request)
    {
        $data = TestimoniController::dataCache();
        $ratings = $data['ratings'];

        $bintang = $request->integer('bintang');
        if ($bintang >= 1 && $bintang <= 5) {
            $ratings = $ratings->where('rating', $bintang);
        }

        if ($request->get('sort') === 'tertinggi') {
            $ratings = $ratings->sortByDesc('rating')->sortByDesc('created_at');
        } else {
            $ratings = $ratings->sortByDesc('created_at');
        }

        $mapped = $ratings->values()->map(function (Rating $rating) {
            return [
                'id' => $rating->id,
                'nama_user' => $rating->user?->name,
                'nama_layanan' => $rating->layanan?->nama_layanan,
                'kode_pesanan' => $rating->pesanan?->kode_pesanan,
                'rating' => $rating->rating,
                'ulasan' => $rating->ulasan,
                'created_at' => $rating->created_at?->toIso8601String(),
                'medias' => $rating->medias->map(fn ($m) => [
                    'url' => Storage::disk('public')->url($m->path),
                    'urutan' => $m->urutan,
                ]),
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Data testimoni berhasil diambil',
            'data' => $mapped,
            'summary' => $data['summary'],
        ], 200);
    }

    /**
     * POST /api/pesanan/{pesanan}/rating
     * Alur 1: simpan/update rating untuk satu layanan dalam pesanan.
     */
    public function storeByPesanan(Request $request, $idPesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $idPesanan)
            ->where('id_user', auth()->id())
            ->first();

        if (! $pesanan) {
            return response()->json(['status' => 'error', 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        if ($pesanan->status !== Pesanan::STATUS_SELESAI) {
            return response()->json(['status' => 'error', 'message' => 'Rating hanya bisa diberikan setelah pesanan selesai'], 403);
        }

        $validated = $this->validateApiRequest($request);

        $fotos = $request->file('foto', []);

        try {
            $this->ratingService->storeForPesanan(
                $pesanan,
                auth()->id(),
                [
                    $validated['id_layanan'] => [
                        'rating' => $validated['rating'],
                        'ulasan' => $validated['ulasan'] ?? null,
                        'foto_baru' => $fotos,
                    ],
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Rating berhasil disimpan',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    // [DISABLED] Alur 2 — endpoint tidak terpakai (route POST /api/rating/layanan dikomentari di routes/api.php).

    /**
     * POST /api/rating/layanan
     * Alur 2: simpan/update rating layanan tanpa pesanan.
     */
    public function storeByLayanan(Request $request)
    {
        $validated = $this->validateApiRequest($request);

        $layanan = Layanan::where('id_layanan', $validated['id_layanan'])->first();
        if (! $layanan) {
            return response()->json(['status' => 'error', 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $fotos = $request->file('foto', []);

        try {
            $this->ratingService->storeForLayanan(
                $layanan,
                auth()->id(),
                [
                    'rating' => $validated['rating'],
                    'ulasan' => $validated['ulasan'] ?? null,
                    'foto_baru' => $fotos,
                ]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Rating berhasil disimpan',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * GET /api/rating/saya — rating yang sudah dibuat user login.
     */
    public function myRatings(Request $request)
    {
        $ratings = Rating::where('id_user', auth()->id())
            ->with(['layanan:id_layanan,nama_layanan', 'pesanan:id_pesanan,kode_pesanan', 'medias'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $ratings->map(function (Rating $rating) {
                return [
                    'id' => $rating->id,
                    'id_layanan' => $rating->id_layanan,
                    'nama_layanan' => $rating->layanan?->nama_layanan,
                    'kode_pesanan' => $rating->pesanan?->kode_pesanan,
                    'rating' => $rating->rating,
                    'ulasan' => $rating->ulasan,
                    'is_tampil' => $rating->is_tampil,
                    'medias' => $rating->medias->map(fn ($m) => Storage::disk('public')->url($m->path)),
                ];
            }),
        ], 200);
    }

    /**
     * Validasi request API rating.
     */
    protected function validateApiRequest(Request $request): array
    {
        $validated = $request->validate([
            'id_layanan' => 'required|exists:layanans,id_layanan',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:500',
            'foto' => 'nullable|array|max:2',
            'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        RatingService::assertCleanContent($validated['ulasan'] ?? null);

        return $validated;
    }
}
