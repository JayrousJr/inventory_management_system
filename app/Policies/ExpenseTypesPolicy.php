<?php

namespace App\Policies;

use App\Models\ExpenseTypes;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpenseTypesPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasPermissionTo('expensetypeViewAny')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ExpenseTypes $expenseTypes): bool
    {
        if ($user->hasPermissionTo('expensetypeView')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo('expensetypeCreate')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ExpenseTypes $expenseTypes): bool
    {
        if ($user->hasPermissionTo('expensetypeEdit')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ExpenseTypes $expenseTypes): bool
    {
        if ($user->hasPermissionTo('expensetypeDelete')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ExpenseTypes $expenseTypes): bool
    {
        if ($user->hasPermissionTo('expensetypeDelete')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ExpenseTypes $expenseTypes): bool
    {
        if ($user->hasPermissionTo('expensetypeDelete')) {
            return true;
        }
        return false;
    }
}
