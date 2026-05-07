<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan; // Model milik hillmi
use App\Models\Layanan;          // Model milik septa
use App\Models\Galeri;           // Model milik septa

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil satu data profil (karena profil cuma 1)
        $profil = ProfilPerusahaan::first();

        // Mengambil semua data layanan dari model Layanan
        $layanans = Layanan::all();

        // Mengambil semua data galeri dari model Galeri
        $galeris = Galeri::latest()->get();

        // Mengirim data ke halaman welcome
        return view('welcome', compact('profil', 'layanans', 'galeris'));
    }
}