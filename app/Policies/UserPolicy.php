<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasPermissionTo('userViewAny')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($model->hasRole(['Sales Person', 'Stock Person', 'Customer', 'Manager'])) {
            return true;
        }
        return $model->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo('usersCreate')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('Manager')) {
            if ($model->hasRole(['Sales Person', 'Stock Person', 'Customer', 'Manager'])) {
                return true;
            }
        } else if ($user->hasRole('Sales Person')) {
            return  $user->id === $model->id;
        } else if ($user->hasRole('Stock Person')) {
            return  $user->id === $model->id;
        }
        // else {
        // if ($model->hasRole(['Sales Person', 'Stock Person', 'Customer', 'Manager', 'System Administrator'])) {
        //     return true;
        // }
        // if ($user->hasRole('System Administrator')) {
        //     if ($model->hasRole(['Sales Person', 'Stock Person', 'Customer', 'Manager', 'System Administrator'])) {
        //         return true;
        //     }
        // }
        return true;
        // return true;
        // }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->hasRole(['System Administrator'])) {
            return true;
        }
        // return $model->id === $user->id;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('usersRestore')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('usersDelete')) {
            return true;
        }
        return false;
    }
}