<?php

namespace App\Policies;

use App\Models\Income;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncomePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Income $income): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->incomes()->where('id', $income->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Income $income): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->incomes()->where('id', $income->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function delete(User $user, Income $income): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->incomes()->where('id', $income->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function restore(User $user, Income $income): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->incomes()->where('id', $income->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function forceDelete(User $user, Income $income): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->incomes()->where('id', $income->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }
}
