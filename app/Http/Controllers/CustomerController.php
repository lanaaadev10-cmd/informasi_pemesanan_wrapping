<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Layanan;
=======
use Illuminate\Support\Facades\Cache;
use App\Models\Layanan;
use App\Models\Galeri;
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610

class CustomerController extends Controller
{
    public function katalog()
    {
        $layanan = Layanan::all();
<<<<<<< HEAD

        return view('customer.katalog', compact('layanan'));
=======
        $profil = Cache::rememberForever('site_profile', function() {
            return \App\Models\ProfilPerusahaan::first() ?? new \App\Models\ProfilPerusahaan();
        });

        return view('landing.katalog.index', compact('layanan', 'profil'));
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610
    }

    public function dashboard()
    {
<<<<<<< HEAD
        $layanan = \App\Models\Layanan::all();
        return view('dashboard', compact('layanan'));
=======
        // 🚀 Ambil data terbaru tanpa cache agar konsisten dengan katalog
        $layanans = Layanan::all();
        $galeris = Galeri::latest()->limit(8)->get();

        $latestOrders = \App\Models\Pesanan::where('id_user', auth()->id())
            ->with(['form', 'details.layanan'])
            ->latest()
            ->limit(5)
            ->get();

        $latestOrder = $latestOrders->first();

        $profil = Cache::rememberForever('site_profile', function() {
            return \App\Models\ProfilPerusahaan::first() ?? new \App\Models\ProfilPerusahaan();
        });

        return view('dashboard.customer.dashboard.index', compact('layanans', 'galeris', 'latestOrder', 'latestOrders', 'profil'));
>>>>>>> bf0334c2b14d316dddb6e466f2be6d6502606610
    }
}