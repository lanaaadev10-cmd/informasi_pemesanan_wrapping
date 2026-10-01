<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Pesanan;
use App\Models\Rating;
use App\Models\User;

class RatingPolicy
{
    /**
     * Boleh buat rating untuk pesanan / booking yang statusnya selesai
     * dan dimiliki user yang sedang login.
     */
    public function create(User $user, Pesanan|Booking|null $source = null): bool
    {
        if ($source === null) {
            return true;
        }

        if ($source instanceof Pesanan) {
            return $source->id_user === $user->id
                && $source->status === Pesanan::STATUS_SELESAI;
        }

        if ($source instanceof Booking) {
            $statusVal = $source->status instanceof \App\Enums\BookingStatus
                ? $source->status->value
                : (string) $source->status;

            return $source->user_id === $user->id && $statusVal === 'completed';
        }

        return false;
    }

    /**
     * Boleh update rating jika rating milik user yang login.
     */
    public function update(User $user, Rating $rating): bool
    {
        return $rating->id_user === $user->id;
    }
}
