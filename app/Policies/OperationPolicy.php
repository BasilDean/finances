<?php

namespace App\Policies;

use App\Models\Operation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Operation $operation): bool
    {
        $budgets = $user->budgets()->get();
        foreach ($budgets as $budget) {
            if ($budget->accounts()->where('account_id', $operation->account_id)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Operation $operation): bool
    {
        $budgets = $user->budgets()->get();
        foreach ($budgets as $budget) {
            if ($budget->accounts()->where('account_id', $operation->account_id)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function delete(User $user, Operation $operation): bool
    {
        $budgets = $user->budgets()->get();
        foreach ($budgets as $budget) {
            if ($budget->accounts()->where('account_id', $operation->account_id)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function restore(User $user, Operation $operation): bool
    {
        $budgets = $user->budgets()->get();
        foreach ($budgets as $budget) {
            if ($budget->accounts()->where('account_id', $operation->account_id)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function forceDelete(User $user, Operation $operation): bool
    {
        $budgets = $user->budgets()->get();
        foreach ($budgets as $budget) {
            if ($budget->accounts()->where('account_id', $operation->account_id)->exists()) {
                return true;
            }
        }
        return false;
    }
}
