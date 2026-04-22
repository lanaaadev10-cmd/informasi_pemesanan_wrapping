<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan; // Panggil modelnya
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data pertama dari database
        $profil = ProfilPerusahaan::first();

        // Kirim datanya ke file dashboard.blade.php
        return view('dashboard', compact('profil'));
    }
}
