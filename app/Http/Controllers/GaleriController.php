<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class GaleriController extends Controller
{
    public function index()
    {
        $galeris = \App\Services\CacheService::getPublicGaleris();
        return view('landing.galeri.index', compact('galeris'));
    }

    public function kategori(string $kategori)
    {
        $galeris = \App\Services\CacheService::getPublicGaleris($kategori);
        return view('landing.galeri.index', compact('galeris') + ['filterKategori' => $kategori]);
    }
}
