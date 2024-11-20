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
        return true;
        // TODO create actual policies
    }

    public function view(User $user, Income $income): bool
    {
        return true;
        // TODO create actual policies
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create actual policies
    }

    public function update(User $user, Income $income): bool
    {
        return true;
        // TODO create actual policies
    }

    public function delete(User $user, Income $income): bool
    {
        return true;
        // TODO create actual policies
    }

    public function restore(User $user, Income $income): bool
    {
        return true;
        // TODO create actual policies
    }

    public function forceDelete(User $user, Income $income): bool
    {
        return true;
        // TODO create actual policies
    }
}
