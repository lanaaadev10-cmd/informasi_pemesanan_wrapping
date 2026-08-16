<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class GaleriController extends Controller
{
    public function index()
    {
        $galeris = Galeri::all();
        return view('landing.galeri.index', compact('galeris'));
    }

    public function kategori(string $kategori)
    {
        $galeris = Galeri::where('kategori', $kategori)->get();
        return view('landing.galeri.index', compact('galeris') + ['filterKategori' => $kategori]);
    }
}
