<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('assign roles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('assign roles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('assign roles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        // Super admin role cannot be edited
        if ($role->name === 'super-admin') {
            return $user->hasRole('super-admin');
        }

        return $user->hasPermissionTo('assign roles');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        // Super admin role cannot be deleted
        if ($role->name === 'super-admin') {
            return false;
        }

        return $user->hasPermissionTo('assign roles');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('assign roles');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        // Super admin role cannot be deleted
        if ($role->name === 'super-admin') {
            return false;
        }

        return $user->hasPermissionTo('assign roles');
    }
}
