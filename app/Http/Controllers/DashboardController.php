<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan; // Model milik septa
use App\Models\Layanan;          // Model milik septa

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil satu data profil (karena profil cuma 1)
        $profil = ProfilPerusahaan::first();

        // Mengambil semua data layanan dari model Layanan
        $layanans = Layanan::all();

        // Mengirim data ke halaman welcome
        return view('welcome', compact('profil', 'layanans'));
    }
}