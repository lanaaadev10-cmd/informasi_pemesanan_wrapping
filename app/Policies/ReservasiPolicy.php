<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Reservasi;
use App\Models\User;

class ReservasiPolicy
{
    /**
     * Admin berhak melihat daftar semua reservasi.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->can('bookings index');
    }

    /**
     * Pelanggan pemilik atau admin berhak melihat detail reservasi.
     */
    public function view(User $user, Booking|Reservasi $booking): bool
    {
        return $user->hasRole('admin') 
            || $user->can('bookings index') 
            || (int) $user->id === (int) $booking->user_id;
    }

    /**
     * Setiap user terverifikasi boleh membuat reservasi.
     */
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    /**
     * Hanya admin yang berhak mengedit data operasional reservasi via dashboard.
     */
    public function update(User $user, Booking|Reservasi $booking): bool
    {
        return $user->hasRole('admin') || $user->can('bookings edit');
    }

    /**
     * Hanya admin yang berhak menghapus reservasi.
     */
    public function delete(User $user, Booking|Reservasi $booking): bool
    {
        return $user->hasRole('admin') || $user->can('bookings delete');
    }

    /**
     * Pelanggan pemilik berhak mengunggah bukti bayar jika status mengizinkan.
     */
    public function uploadPayment(User $user, Booking|Reservasi $booking): bool
    {
        return (int) $user->id === (int) $booking->user_id && $booking->bisaUploadBukti();
    }

    /**
     * Pelanggan pemilik berhak membatalkan reservasi jika status masih pending.
     */
    public function cancel(User $user, Booking|Reservasi $booking): bool
    {
        return (int) $user->id === (int) $booking->user_id && $booking->bisaDibatalkan();
    }
}
