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
        return true;
        // TODO create actual policies;
    }

    public function view(User $user, Expense $Expense): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function update(User $user, Expense $Expense): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function delete(User $user, Expense $Expense): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function restore(User $user, Expense $Expense): bool
    {
        return true;
        // TODO create actual policies;
    }

    public function forceDelete(User $user, Expense $Expense): bool
    {
        return true;
        // TODO create actual policies;
    }
}
