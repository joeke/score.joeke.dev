<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $user, User $model): bool
    {
        return ($user->id === $model->created_by) || $user->isAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        return ($user->id === $model->created_by) || $user->isAdmin();
    }

    public function forceDelete(User $user, User $model): bool
    {
        return ($user->id === $model->created_by) || $user->isAdmin();
    }
}
