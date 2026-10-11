<?php

namespace App\Policies;

use App\Models\Pesanan;
use App\Models\User;

class PesananPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('pesanans index');
    }

    public function view(User $user, Pesanan $pesanan): bool
    {
        return $user->can('pesanans index') || $user->id === $pesanan->id_user;
    }

    public function create(User $user): bool
    {
        return $user->can('pesanans create');
    }

    public function update(User $user, Pesanan $pesanan): bool
    {
        return $user->can('pesanans edit');
    }

    public function delete(User $user, Pesanan $pesanan): bool
    {
        return $user->can('pesanans delete');
    }

    public function restore(User $user, Pesanan $pesanan): bool
    {
        return $user->can('pesanans edit');
    }

    public function forceDelete(User $user, Pesanan $pesanan): bool
    {
        return $user->can('pesanans delete');
    }
}
