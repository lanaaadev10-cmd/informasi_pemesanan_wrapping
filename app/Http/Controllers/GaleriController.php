<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use Illuminate\Support\Facades\Cache;

class GaleriController extends Controller
{
    public function index()
    {
        $galeri = Cache::rememberForever('site_galeris', function () {
            return Galeri::latest()->get();
        });

        return view('landing.galeri.index', compact('galeri'));
    }
}
