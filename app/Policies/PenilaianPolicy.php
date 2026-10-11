<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Pesanan;
use App\Models\Penilaian;
use App\Models\Rating;
use App\Models\Reservasi;
use App\Models\User;

class PenilaianPolicy
{
    /**
     * Boleh buat rating untuk pesanan / reservasi yang statusnya selesai
     * dan dimiliki user yang sedang login.
     */
    public function create(User $user, Pesanan|Booking|Reservasi|null $source = null): bool
    {
        if ($source === null) {
            return true;
        }

        if ($source instanceof Pesanan) {
            return $source->id_user === $user->id
                && $source->status === Pesanan::STATUS_SELESAI;
        }

        if ($source instanceof Booking || $source instanceof Reservasi) {
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
    public function update(User $user, Rating|Penilaian $rating): bool
    {
        return $rating->id_user === $user->id;
    }
}
