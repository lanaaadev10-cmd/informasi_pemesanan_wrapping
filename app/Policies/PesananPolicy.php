<?php

namespace App\Policies;

use App\Models\Pesanan;
use App\Models\User;

class PesananPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Pesanan $pesanan): bool
    {
        return $user->hasRole('admin') || $user->id === $pesanan->id_user;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Pesanan $pesanan): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Pesanan $pesanan): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Pesanan $pesanan): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Pesanan $pesanan): bool
    {
        return $user->hasRole('admin');
    }
}
