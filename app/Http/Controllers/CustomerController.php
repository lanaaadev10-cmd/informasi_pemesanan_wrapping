<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Layanan;
use App\Models\Galeri;

class CustomerController extends Controller
{
    public function katalog()
    {
        $layanan = Layanan::all();
        $profil = Cache::remember('site_profile', 3600, function() {
            return \App\Models\ProfilPerusahaan::first() ?? new \App\Models\ProfilPerusahaan();
        });

        return view('landing.katalog.index', compact('layanan', 'profil'));
    }

    public function dashboard()
    {
        // 🚀 Ambil data terbaru tanpa cache agar konsisten dengan katalog
        $layanans = Layanan::all();
        $galeris = Galeri::latest()->limit(8)->get();

        $latestOrders = \App\Models\Pesanan::where('id_user', Auth::id())
            ->with(['form', 'details.layanan'])
            ->latest()
            ->limit(5)
            ->get();

        $latestOrder = $latestOrders->first();

        $profil = Cache::remember('site_profile', 3600, function() {
            return \App\Models\ProfilPerusahaan::first() ?? new \App\Models\ProfilPerusahaan();
        });

        return view('dashboard.customer.dashboard.index', compact('layanans', 'galeris', 'latestOrder', 'latestOrders', 'profil'));
    }
}