<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HardwareModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class HardwareModelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_hardware::model');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HardwareModel $hardwareModel): bool
    {
        return $user->can('view_hardware::model');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_hardware::model');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HardwareModel $hardwareModel): bool
    {
        return $user->can('update_hardware::model');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HardwareModel $hardwareModel): bool
    {
        return $user->can('delete_hardware::model');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_hardware::model');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, HardwareModel $hardwareModel): bool
    {
        return $user->can('force_delete_hardware::model');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_hardware::model');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, HardwareModel $hardwareModel): bool
    {
        return $user->can('restore_hardware::model');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_hardware::model');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, HardwareModel $hardwareModel): bool
    {
        return $user->can('replicate_hardware::model');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_hardware::model');
    }
}
