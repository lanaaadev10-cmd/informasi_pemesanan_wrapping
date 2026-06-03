<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Galeri;

class CustomerController extends Controller
{
    public function katalog()
    {
        $layanan = Layanan::all();

        return view('landing.katalog.index', compact('layanan'));
    }

    public function dashboard()
    {
        $layanans = Layanan::all();
        $galeris = Galeri::latest()->limit(8)->get();

        $latestOrders = \App\Models\Pesanan::where('id_user', auth()->id())
            ->with(['form', 'details.layanan'])
            ->latest()
            ->limit(5)
            ->get();

        $latestOrder = $latestOrders->first();

        return view('dashboard.customer.dashboard.index', compact('layanans', 'galeris', 'latestOrder', 'latestOrders'));
    }
}