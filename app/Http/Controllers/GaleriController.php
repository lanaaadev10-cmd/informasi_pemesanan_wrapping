<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\Cache;

class GaleriController extends Controller
{
    public function index()
    {
        $profil = Cache::remember('site_profile', 3600, function () {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });

        $galeri = Cache::remember('site_galeris', 3600, function () {
            return Galeri::latest()->get();
        });

        return view('landing.galeri.index', compact('galeri', 'profil'));
    }
}
