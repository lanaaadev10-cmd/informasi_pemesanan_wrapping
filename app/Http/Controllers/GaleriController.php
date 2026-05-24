<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Galeri::latest()->get();
        $profil = \App\Models\ProfilPerusahaan::first() ?? new \App\Models\ProfilPerusahaan();

        return view('landing.galeri.index', compact('galeri', 'profil'));
    }
}
