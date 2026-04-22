<?php

namespace App\Http\Controllers;

use App\Models\Layanan;

class CustomerController extends Controller
{
    public function katalog()
    {
        $layanan = Layanan::all();

        return view('customer.katalog', compact('layanan'));
    }
}