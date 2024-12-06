<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Account $account): bool
    {
        foreach ($user->budgets()->get() as $budget) {
            if ($budget->accounts()->where('account_id', $account->id)->exists()) {
                return true;
            }
        }
        return false;
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function update(User $user, Account $account): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function delete(User $user, Account $account): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function restore(User $user, Account $account): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function forceDelete(User $user, Account $account): bool
    {
        return true;
        // TODO create actual policies;
    }
}
