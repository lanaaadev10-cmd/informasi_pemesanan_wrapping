<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\ProfilPerusahaan;
use Illuminate\Support\Facades\Cache;

class GaleriController extends Controller
{
    public function index()
    {
        $profil = Cache::rememberForever('site_profile', function () {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });

        $galeri = Cache::rememberForever('site_galeris', function () {
            return Galeri::latest()->get();
        });

        return view('landing.galeri.index', compact('galeri', 'profil'));
    }
}
