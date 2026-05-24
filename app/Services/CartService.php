<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Exception;

class CartService
{
    /**
     * Menambahkan item ke keranjang dengan validasi limit 3.
     */
    public function addToCart(int $packageId)
    {
        $user = Auth::user();
        
        $currentCartCount = Cart::where('user_id', $user->id)->count();

        if ($currentCartCount >= 3) {
            throw new Exception("Anda hanya dapat memilih maksimal 3 paket layanan.");
        }

        // Cek jika item sudah ada untuk menghindari duplikasi di keranjang
        $exists = Cart::where('user_id', $user->id)
                      ->where('layanan_id', $packageId)
                      ->exists();

        if ($exists) {
            throw new Exception("Paket ini sudah ada di keranjang Anda.");
        }

        return Cart::create([
            'user_id' => $user->id,
            'layanan_id' => $packageId,
        ]);
    }
}
