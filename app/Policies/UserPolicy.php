<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function viewAny(User $authUser)
    {
        return $authUser->hasRole('superadmin') ||
            ($authUser->hasRole('manager') && $authUser->hasPermissionTo('view_any users'));
    }

    public function view(User $authUser, User $targetUser)
    {
        if ($authUser->hasRole('superadmin')) {
            return true;
        }

        if ($authUser->hasRole('manager') && $authUser->hasPermissionTo('view users')) {
            // Additional tenant scope check
            return $authUser->tenant_id === $targetUser->tenant_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $authUser)
    {
        // Global superadmin bypass
        if ($authUser->hasRole('superadmin')) {
            return true;
        }

        // Manager with create permission and valid tenant
        if (
            $authUser->hasRole('manager') &&
            $authUser->hasPermissionTo('create users') &&
            $authUser->tenant_id === tenant('id')
        ) {
            return true;
        }

        return false;
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Superadmin can update any user
        if ($user->hasRole('superadmin')) {
            return true;
        }

        // Manager can update users in their tenant
        if ($user->hasRole('manager') && $user->tenant_id === $model->tenant_id) {
            return $user->hasPermissionTo('update users');
        }

        // Employee cannot update users
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
