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
        $profil = Cache::rememberForever('site_profile', function() {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });

        $layanans = Cache::rememberForever('site_layanans', function() {
            return Layanan::all();
        });

        $galeris = Cache::rememberForever('site_galeris', function() {
            return Galeri::latest()->limit(8)->get();
        });
        
        $landingFiturs = collect([]);

        $testimonis = collect($profil->testimonis_json ?? [])
            ->map(fn($item) => (object)$item);

        $recentActivity = Cache::remember('recent_activity', 60, function() {
            return \App\Models\Pesanan::with('user')
                ->latest()
                ->limit(10)
                ->get();
        });

        return view('landing.beranda.index', compact(
            'profil',
            'layanans',
            'galeris',
            'landingFiturs',
            'testimonis',
            'recentActivity'
        ));
    }

    public function profile()
    {
        $profil = Cache::rememberForever('site_profile', function() {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });

        return view('landing.profil.index', compact('profil'));
    }

    public function tentangKami()
    {
        $profil = Cache::rememberForever('site_profile', function() {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });

        return view('landing.tentang-kami.index', compact('profil'));
    }

    public function layanan()
    {
        $profil = Cache::rememberForever('site_profile', function() {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });

        $layanans = Cache::rememberForever('site_layanans', function() {
            return Layanan::all();
        });

        return view('landing.layanan.index', compact('profil', 'layanans'));
    }
}
