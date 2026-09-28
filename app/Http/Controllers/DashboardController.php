<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Layanan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $galeris = Galeri::all();
        return view('landing.beranda.index', compact('galeris'));
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

        $layanans = Layanan::all();
        $ratingSummary = \App\Http\Controllers\TestimoniController::summaryPerLayananKeyed();

        return view('landing.layanan.index', compact('layanans', 'ratingSummary'));
    }

    public function kebijakanPrivasi()
    {
        return view('landing.kebijakan-privasi.index');
    }
}
