<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Layanan;
use App\Models\Galeri;

class DashboardController extends Controller
{
    public function index()
    {
        $layanans = Cache::rememberForever('site_layanans', function() {
            return Layanan::all();
        });

        $galeris = Cache::rememberForever('site_galeris', function() {
            return Galeri::latest()->limit(8)->get();
        });

        $landingFiturs = Cache::rememberForever('site_landing_fiturs', function() {
            return \App\Models\LandingFitur::aktif()->orderBy('urutan')->get();
        });

        $recentActivity = Cache::remember('recent_activity', 60, function() {
            return \App\Models\Pesanan::with('user')
                ->latest()
                ->limit(10)
                ->get();
        });

        return view('landing.beranda.index', compact(
            'layanans',
            'galeris',
            'landingFiturs',
            'recentActivity'
        ));
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
        $layanans = Cache::rememberForever('site_layanans', function() {
            return Layanan::all();
        });

        return view('landing.layanan.index', compact('layanans'));
    }
}
