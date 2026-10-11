<?php

namespace App\Policies;

use App\Models\User;

class PenggunaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users index');
    }

    public function view(User $user, User $model): bool
    {
        return $user->can('users index');
    }

    public function create(User $user): bool
    {
        return $user->can('users create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->can('users edit');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can('users delete') && $user->id !== $model->id;
    }

    public function restore(User $user, User $model): bool
    {
        return $user->can('users edit');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->can('users delete') && $user->id !== $model->id;
    }
}
