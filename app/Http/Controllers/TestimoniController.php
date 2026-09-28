<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestimoniController extends Controller
{
    /**
     * Data daftar ulasan publik (yang is_tampil = true).
     * Pakai cache kunci `testimoni_ratings`, invalidasi otomatis saat Rating saved/deleted.
     */
    public static function dataCache()
    {
        return Cache::remember('testimoni_ratings', 60 * 60 * 24, function () {
            $ratings = Rating::with(['user:id,name', 'layanan:id_layanan,nama_layanan', 'pesanan:id_pesanan,kode_pesanan', 'medias'])
                ->where('is_tampil', true)
                ->latest()
                ->get();

            $perLayanan = $ratings
                ->groupBy('id_layanan')
                ->map(function ($items) {
                    $label = $items->first()?->layanan?->nama_layanan ?? 'Layanan';

                    return [
                        'id_layanan' => (int) $items->first()->id_layanan,
                        'nama_layanan' => $label,
                        'avg' => round($items->avg('rating'), 2),
                        'count' => $items->count(),
                    ];
                })
                ->values();

            return [
                'ratings' => $ratings,
                'summary' => [
                    'total' => $ratings->count(),
                    'average' => $ratings->isEmpty() ? 0 : round($ratings->avg('rating'), 2),
                    'distribution' => [
                        5 => $ratings->where('rating', 5)->count(),
                        4 => $ratings->where('rating', 4)->count(),
                        3 => $ratings->where('rating', 3)->count(),
                        2 => $ratings->where('rating', 2)->count(),
                        1 => $ratings->where('rating', 1)->count(),
                    ],
                    'per_layanan' => $perLayanan,
                ],
            ];
        });
    }

    /**
     * Mapping rata-rata rating per layanan (keyed by id_layanan) untuk kartu katalog.
     * Contoh item: ['id_layanan' => 1, 'nama_layanan' => '...', 'avg' => 4.5, 'count' => 12].
     */
    public static function summaryPerLayananKeyed(): \Illuminate\Support\Collection
    {
        return collect(self::dataCache()['summary']['per_layanan'])
            ->keyBy('id_layanan');
    }

    /**
     * Halaman publik /testimoni.
     * Dukung filter bintang (bintang=N) dan sort (terbaru|tertinggi).
     */
    public function index(Request $request)
    {
        $data = self::dataCache();
        $ratings = $data['ratings'];
        $summary = $data['summary'];

        $bintang = $request->integer('bintang');
        if ($bintang >= 1 && $bintang <= 5) {
            $ratings = $ratings->where('rating', $bintang);
        }

        if ($request->get('sort') === 'tertinggi') {
            $ratings = $ratings->sortByDesc('rating')->sortByDesc('created_at');
        } else {
            $ratings = $ratings->sortByDesc('created_at');
        }

        return view('landing.testimoni.index', compact('ratings', 'summary', 'bintang'));
    }
}
