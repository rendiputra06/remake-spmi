<?php

namespace App\Policies;

use App\Models\Standard;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StandardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view standards');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('view standards');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create standards');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('edit standards');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('delete standards');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('edit standards');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Standard $standard): bool
    {
        return $user->hasPermissionTo('delete standards');
    }
}
