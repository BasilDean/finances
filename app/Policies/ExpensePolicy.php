<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Expense $expense): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->expenses()->where('id', $expense->id)->exists()) {
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

    public function update(User $user, Expense $expense): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->expenses()->where('id', $expense->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function delete(User $user, Expense $expense): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->expenses()->where('id', $expense->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function restore(User $user, Expense $expense): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->expenses()->where('id', $expense->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function forceDelete(User $user, Expense $expense): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->expenses()->where('id', $expense->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }
}
