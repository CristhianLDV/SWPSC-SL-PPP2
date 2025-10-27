<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Alert;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlertPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_alerts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alert $alert): bool
    {
        return $user->can('view_alerts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_alerts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alert $alert): bool
    {
        return $user->can('update_alerts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alert $alert): bool
    {
        return $user->can('delete_alerts');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_alerts');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Alert $alert): bool
    {
        return $user->can('force_delete_alerts');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_alerts');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Alert $alert): bool
    {
        return $user->can('restore_alerts');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_alerts');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Alert $alert): bool
    {
        return $user->can('replicate_alerts');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_alerts');
    }
}
