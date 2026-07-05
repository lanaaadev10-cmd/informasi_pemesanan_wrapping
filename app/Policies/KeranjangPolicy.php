<?php

namespace App\Policies;

use App\Models\Keranjang;
use App\Models\User;

class KeranjangPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Keranjang $keranjang): bool
    {
        return $user->id === $keranjang->id_user;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Keranjang $keranjang): bool
    {
        return $user->id === $keranjang->id_user;
    }

    public function delete(User $user, Keranjang $keranjang): bool
    {
        return $user->id === $keranjang->id_user;
    }

    public function restore(User $user, Keranjang $keranjang): bool
    {
        return $user->id === $keranjang->id_user;
    }

    public function forceDelete(User $user, Keranjang $keranjang): bool
    {
        return $user->id === $keranjang->id_user;
    }
}
