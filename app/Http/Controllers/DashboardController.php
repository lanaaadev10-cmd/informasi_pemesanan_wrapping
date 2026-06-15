<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Layanan;
use App\Models\Galeri;

/**
 * DashboardController
 *
 * Menangani halaman-halaman landing page publik (beranda, profil, tentang kami, layanan).
 * Data $profil sudah otomatis tersedia di semua view melalui ShareSettingsToViews middleware.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $layanans = Cache::rememberForever('site_layanans', fn () => Layanan::all());

        $mobil = Galeri::where('kategori', 'Mobil')->latest()->first();
        $motor = Galeri::where('kategori', 'Sepeda Motor')->latest()->first();
        $kapal = Galeri::where('kategori', 'Kapal Laut')->latest()->first();
        $galeris = collect([$mobil, $motor, $kapal])->filter();

        $recentActivity = Cache::remember('recent_activity', 60, fn () =>
            \App\Models\Pesanan::with('user')->latest()->limit(10)->get()
        );

        return view('landing.beranda.index', compact('layanans', 'galeris', 'recentActivity'));
    }

    public function profile()
    {
        return view('landing.profil.index');
    }

    public function tentangKami()
    {
        return view('landing.tentang-kami.index');
    }

    public function layanan()
    {
        $layanans = Cache::rememberForever('site_layanans', fn () => Layanan::all());

        return view('landing.layanan.index', compact('layanans'));
    }
}
