<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('landing.beranda.index');
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
        return view('landing.layanan.index');
    }
}
