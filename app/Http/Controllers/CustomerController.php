<?php

namespace App\Http\Controllers;

use App\Models\Layanan;

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
        $galeris = [
            ['judul' => 'Tesla Model S', 'foto' => 'images/galeri/tesla-model-s.jpg', 'deskripsi' => 'Matte Grey / Blue satin wrap'],
            ['judul' => 'Range Rover Sport', 'foto' => 'images/galeri/range-rover-sport.jpg', 'deskripsi' => 'Satin Liquid Silver Wrap'],
            ['judul' => 'Ferrari F8 Tributo', 'foto' => 'images/galeri/ferrari-f8.jpg', 'deskripsi' => 'Satin Metallic Gold Yellow'],
            ['judul' => 'Porsche 911 GT3', 'foto' => 'images/galeri/porsche-911.jpg', 'deskripsi' => 'Matte Racing Green'],
            ['judul' => 'Mercedes-Benz S-Class', 'foto' => 'images/galeri/mercedes-s-class.jpg', 'deskripsi' => 'Gloss Diamond White'],
            ['judul' => 'Lamborghini Urus', 'foto' => 'images/galeri/lamborghini-urus.jpg', 'deskripsi' => 'Satin Armour Grey'],
        ];

        $latestOrders = \App\Models\Pesanan::where('id_user', auth()->id())
            ->with(['form', 'details.layanan'])
            ->latest()
            ->limit(5)
            ->get();

        $latestOrder = $latestOrders->first();

        return view('dashboard.customer.dashboard.index', compact('layanans', 'galeris', 'latestOrder', 'latestOrders'));
    }
}