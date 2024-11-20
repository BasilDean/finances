<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        // TODO create policies;
    }

    public function view(User $user, Budget $budget): bool
    {
        return true;
        // TODO create policies;
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create policies;
    }

    public function update(User $user, Budget $budget): bool
    {
        return true;
        // TODO create policies;
    }

    public function delete(User $user, Budget $budget): bool
    {
        return true;
        // TODO create policies;
    }

    public function restore(User $user, Budget $budget): bool
    {
        return true;
        // TODO create policies;
    }

    public function forceDelete(User $user, Budget $budget): bool
    {
        return true;
        // TODO create policies;
    }
}
