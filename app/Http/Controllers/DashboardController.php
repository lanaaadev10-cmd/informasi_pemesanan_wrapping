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
        // 🚀 Caching Profil & Layanan (Auto-refresh via booted model)
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

        // Testimonis diambil dari JSON di ProfilPerusahaan (model Testimoni terpisah tidak dipakai)
        $testimonis = collect($profil->testimonis_json ?? [])
            ->map(fn($item) => (object)$item);

        // Aktivitas real-time di-cache sebentar (1 menit) agar tetap terasa "hidup"
        $recentActivity = Cache::remember('recent_activity', 60, function() {
            return \App\Models\Pesanan::with('user')
                ->latest()
                ->limit(10)
                ->get();
        });

        return view('frontend.beranda.index', compact(
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
        
        return view('frontend.profil.index', compact('profil'));
    }

    /**
     * Menampilkan halaman Tentang Kami
     */
    public function tentangKami()
    {
        $profil = Cache::rememberForever('site_profile', function() {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });
        
        return view('frontend.tentang-kami.index', compact('profil'));
    }

    /**
     * Menampilkan halaman Layanan
     */
    public function layanan()
    {
        $profil = Cache::rememberForever('site_profile', function() {
            return ProfilPerusahaan::first() ?? new ProfilPerusahaan();
        });
        
        return view('frontend.layanan.index', compact('profil'));
    }
}
