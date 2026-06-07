<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;

class GaleriApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Galeri::latest();

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        $perPage = $request->get('per_page', 12);
        $galeris = $query->paginate($perPage);

        $galeris->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id_galeri,
                'judul' => $item->judul,
                'sub_judul' => $item->sub_judul,
                'deskripsi' => $item->deskripsi,
                'kategori' => $item->kategori,
                'jenis' => $item->jenis,
                'gambar_url' => asset('storage/' . $item->foto),
                'thumbnail_url' => asset('storage/' . $item->foto),
                'badge_text' => $item->badge_text,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $galeris->items(),
            'pagination' => [
                'current_page' => $galeris->currentPage(),
                'total' => $galeris->total(),
                'per_page' => $galeris->perPage(),
                'last_page' => $galeris->lastPage(),
                'has_more' => $galeris->hasMorePages(),
            ],
        ]);
    }

    public function categories()
    {
        $all = Galeri::select('kategori')
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori')
            ->map(fn($k) => [
                'slug' => $k,
                'label' => 'Galeri Wrapping ' . ucfirst($k),
                'count' => Galeri::where('kategori', $k)->count(),
            ]);

        return response()->json([
            'status' => 'success',
            'data' => $all,
        ]);
    }

    public function jenisList($kategori)
    {
        $jenisList = Galeri::select('jenis')
            ->where('kategori', $kategori)
            ->whereNotNull('jenis')
            ->distinct()
            ->pluck('jenis');

        return response()->json([
            'status' => 'success',
            'data' => $jenisList,
        ]);
    }
}
