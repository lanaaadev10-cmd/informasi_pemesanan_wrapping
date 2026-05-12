<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Models\Layanan;
use App\Models\Galeri;

class CustomerController extends Controller
{
    public function katalog()
    {
        // Cache data layanan selama 1 jam
        $layanan = Cache::remember('katalog_layanans', 3600, function () {
            return Layanan::all();
        });

        return view('frontend.katalog.index', compact('layanan'));
    }

    public function dashboard()
    {
        // Untuk dashboard member, kita cache sebentar saja (10 menit)
        $layanans = Cache::remember('dashboard_layanans', 600, function () {
            return Layanan::all();
        });

        $galeris = Cache::remember('dashboard_galeris', 600, function () {
            return Galeri::latest()->limit(8)->get();
        });

        return view('customer.dashboard.index', compact('layanans', 'galeris'));
    }
}