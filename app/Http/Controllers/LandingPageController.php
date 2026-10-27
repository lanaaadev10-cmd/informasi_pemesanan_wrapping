<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * Controller untuk Halaman Publik (Landing Page)
 *
 * Menangani rute-rute publik: Beranda, Profil Perusahaan, Tentang Kami,
 * Layanan publik, dan Kebijakan Privasi.
 */
class LandingPageController extends Controller
{
    public function index()
    {
        $galeris = \App\Services\CacheService::getLandingGaleris(6);
        $layanans = \App\Services\CacheService::getSiteLayanans();
        $ratingSummary = TestimoniController::dataCache()['summary'];
        
        $stats = \App\Services\CacheService::getLandingStats();
        $minHarga = $stats['minHarga'];
        $totalKlien = $stats['totalKlien'];

        return view('landing.beranda.index', compact(
            'galeris',
            'layanans',
            'ratingSummary',
            'minHarga',
            'totalKlien'
        ));
    }

    public function profile()
    {
        return view('landing.tentang-kami.index');
    }

    public function tentangKami()
    {
        return view('landing.tentang-kami.index');
    }

    public function layanan()
    {
        if (auth()->check()) {
            return redirect()->route('katalog.user');
        }

        $layanans = \App\Services\CacheService::getSiteLayanans();
        $galeris = \App\Services\CacheService::getLandingGaleris(4);
        $ratingSummary = \App\Http\Controllers\TestimoniController::summaryPerLayananKeyed();

        return view('landing.layanan.index', compact('layanans', 'ratingSummary', 'galeris'));
    }

    public function kebijakanPrivasi()
    {
        return view('landing.kebijakan-privasi.index');
    }
}
