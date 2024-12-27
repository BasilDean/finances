<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Purchase $purchase): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->purchases()->where('id', $purchase->id)->exists()) {
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

    public function update(User $user, Purchase $purchase): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->purchases()->where('id', $purchase->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function delete(User $user, Purchase $purchase): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->purchases()->where('id', $purchase->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function restore(User $user, Purchase $purchase): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->purchases()->where('id', $purchase->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function forceDelete(User $user, Purchase $purchase): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            foreach ($budget->accounts()->get() as $account) {
                if ($account->purchases()->where('id', $purchase->id)->exists()) {
                    return true;
                }
            }
        }
        return false;
    }
}
