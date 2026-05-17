<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilPerusahaan;
use App\Models\Layanan;
use App\Models\Galeri;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama (Welcome) dengan data profil, layanan, dan galeri.
     */
    public function index()
    {
        $profil = ProfilPerusahaan::first();
        $layanans = Layanan::all();
        $galeris = Galeri::latest()->get();

        return view('welcome', compact('profil', 'layanans', 'galeris'));
    }

    /**
     * Baru saya tambah: Method profile
     * Fungsinya untuk mengambil data profil perusahaan dan menampilkannya ke halaman 'profil' yang baru kita buat (Frontend Premium).
     */
    public function profile()
    {
        $profil = ProfilPerusahaan::first();
        return view('profil', compact('profil'));
    }
}