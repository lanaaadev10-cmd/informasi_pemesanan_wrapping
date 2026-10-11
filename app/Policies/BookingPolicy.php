<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Admin berhak melihat daftar semua booking.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->can('bookings index');
    }

    /**
     * Pelanggan pemilik atau admin berhak melihat detail booking.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin') 
            || $user->can('bookings index') 
            || (int) $user->id === (int) $booking->user_id;
    }

    /**
     * Setiap user terverifikasi boleh membuat booking.
     */
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    /**
     * Hanya admin yang berhak mengedit data operasional booking via dashboard.
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin') || $user->can('bookings edit');
    }

    /**
     * Hanya admin yang berhak menghapus booking.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin') || $user->can('bookings delete');
    }

    /**
     * Pelanggan pemilik berhak mengunggah bukti bayar jika status mengizinkan.
     */
    public function uploadPayment(User $user, Booking $booking): bool
    {
        return (int) $user->id === (int) $booking->user_id && $booking->bisaUploadBukti();
    }

    /**
     * Pelanggan pemilik berhak membatalkan booking jika status mengizinkan.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        return (int) $user->id === (int) $booking->user_id && $booking->bisaDibatalkan();
    }
}
