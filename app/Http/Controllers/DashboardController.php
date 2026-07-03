<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ProfilPerusahaan;
use App\Models\Layanan;
use App\Models\Galeri;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama (Welcome) dengan data profil, layanan, dan galeri.
     */
    public function index()
    {
        return view('landing.beranda.index');
    }

    public function profile()
    {
        return view('landing.profil.index');
    }

    /**
     * Menampilkan halaman Tentang Kami
     */
    public function tentangKami()
    {
        return view('landing.tentang-kami.index');
    }

    /**
     * Menampilkan halaman Layanan
     */
    public function layanan()
    {
        return view('landing.layanan.index');
    }
}
