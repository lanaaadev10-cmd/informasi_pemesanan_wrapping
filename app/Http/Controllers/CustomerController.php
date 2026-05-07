<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Galeri;  

class CustomerController extends Controller
{
    public function katalog()
    {
        $layanan = Layanan::all();

        return view('customer.katalog', compact('layanan'));
    }

    public function dashboard()
    {
        $layanans = \App\Models\Layanan::all();
        $profil = \App\Models\ProfilPerusahaan::first();
        $galeris = \App\Models\Galeri::latest()->get();
        return view('dashboard', compact('layanans', 'profil', 'galeris'));
    }
}