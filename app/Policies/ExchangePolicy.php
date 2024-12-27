<?php

namespace App\Policies;

use App\Models\Exchange;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExchangePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Exchange $exchange): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            if ($budget->accounts()->where('account_id', $exchange->account_from)->exists() && $budget->accounts()->where('account_id', $exchange->account_to)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Exchange $exchange): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            if ($budget->accounts()->where('account_id', $exchange->account_from)->exists() && $budget->accounts()->where('account_id', $exchange->account_to)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function delete(User $user, Exchange $exchange): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            if ($budget->accounts()->where('account_id', $exchange->account_from)->exists() && $budget->accounts()->where('account_id', $exchange->account_to)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function restore(User $user, Exchange $exchange): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            if ($budget->accounts()->where('account_id', $exchange->account_from)->exists() && $budget->accounts()->where('account_id', $exchange->account_to)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function forceDelete(User $user, Exchange $exchange): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            if ($budget->accounts()->where('account_id', $exchange->account_from)->exists() && $budget->accounts()->where('account_id', $exchange->account_to)->exists()) {
                return true;
            }
        }
        return false;
    }
}
