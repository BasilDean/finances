<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        // TODO create actual policies
    }

    public function view(User $user, Category $category): bool
    {
        return true;
        // TODO create actual policies
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create actual policies
    }

    public function update(User $user, Category $category): bool
    {
        return true;
        // TODO create actual policies
    }

    public function delete(User $user, Category $category): bool
    {
        return true;
        // TODO create actual policies
    }

    public function restore(User $user, Category $category): bool
    {
        return true;
        // TODO create actual policies
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return true;
        // TODO create actual policies
    }
}
