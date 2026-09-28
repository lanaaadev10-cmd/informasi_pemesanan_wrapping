<?php

namespace App\Policies;

use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    /**
     * Boleh buat rating untuk pesanan yang statusnya selesai
     * dan dimiliki user yang sedang login.
     */
    public function create(User $user, ?Pesanan $pesanan = null): bool
    {
        if ($pesanan === null) {
            return true;
        }

        return $pesanan->id_user === $user->id
            && $pesanan->status === Pesanan::STATUS_SELESAI;
    }

    /**
     * Boleh update rating jika rating milik user yang login.
     */
    public function update(User $user, Rating $rating): bool
    {
        return $rating->id_user === $user->id;
    }
}
