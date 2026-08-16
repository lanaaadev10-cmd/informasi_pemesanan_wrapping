<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Galeri;

class GaleriApiController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Galeri::all()]);
    }

    public function categories()
    {
        $categories = Galeri::select('kategori')->distinct()->whereNotNull('kategori')->get()->pluck('kategori');
        return response()->json(['data' => $categories]);
    }

    public function jenisList(string $kategori)
    {
        $items = Galeri::where('kategori', $kategori)->get();
        return response()->json(['data' => $items]);
    }
}
