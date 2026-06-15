<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Support\Facades\Cache;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Cache::rememberForever('site_galeris', fn () =>
            Galeri::latest()->get()
        );

        $categories = Galeri::select('kategori')
            ->whereNotNull('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('landing.galeri.index', compact('galeri', 'categories'));
    }

    public function kategori($kategori)
    {
        $kategoriSlug = strtolower($kategori);
        $kategoriLabel = 'Galeri Wrapping ' . ucfirst($kategoriSlug);

        $galeris = Galeri::where('kategori', $kategoriSlug)
            ->latest()
            ->paginate(12);

        $jenisList = Galeri::select('jenis')
            ->where('kategori', $kategoriSlug)
            ->whereNotNull('jenis')
            ->distinct()
            ->pluck('jenis');

        $allCount = Galeri::where('kategori', $kategoriSlug)->count();

        return view('landing.galeri.kategori', compact(
            'galeris',
            'kategoriSlug',
            'kategoriLabel',
            'jenisList',
            'allCount'
        ));
    }
}
