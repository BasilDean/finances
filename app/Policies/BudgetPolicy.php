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
        return $user !== null;
    }

    public function view(User $user, Budget $budget): bool
    {
        return $user->budgets()->where('budget_id', $budget->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Budget $budget): bool
    {
        return $user->budgets()->where('budget_id', $budget->id)->exists();
    }

    public function delete(User $user, Budget $budget): bool
    {
        return $user->budgets()->where('budget_id', $budget->id)->exists();
    }

    public function restore(User $user, Budget $budget): bool
    {
        return $user->budgets()->where('budget_id', $budget->id)->exists();
    }

    public function forceDelete(User $user, Budget $budget): bool
    {
        return $user->budgets()->where('budget_id', $budget->id)->exists();
    }
}
