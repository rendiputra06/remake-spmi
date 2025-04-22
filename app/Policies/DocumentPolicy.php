<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view documents');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        if ($document->visibility === 'public') {
            return true;
        }

        if ($document->visibility === 'private' && $user->id === $document->uploaded_by) {
            return true;
        }

        if ($document->visibility === 'restricted') {
            // Check if user is in same faculty or department or has specific permission
            if ($document->faculty_id && $user->profile->faculty_id === $document->faculty_id) {
                return true;
            }

            if ($document->department_id && $user->profile->department_id === $document->department_id) {
                return true;
            }

            if ($document->unit_id && $user->profile->unit_id === $document->unit_id) {
                return true;
            }
        }

        return $user->hasPermissionTo('view documents');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create documents');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        // Owner can always update their own document
        if ($user->id === $document->uploaded_by) {
            return true;
        }

        return $user->hasPermissionTo('edit documents');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        // Owner can always delete their own document
        if ($user->id === $document->uploaded_by) {
            return true;
        }

        return $user->hasPermissionTo('delete documents');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->hasPermissionTo('edit documents');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->hasPermissionTo('delete documents');
    }
}
