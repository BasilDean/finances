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
        return $user !== null;
    }

    public function view(User $user, Category $category): bool
    {
        return $user !== null;
    }

    public function create(User $user): bool
    {
        return $user->roles->contains('admin');
    }

    public function update(User $user, Category $category): bool
    {
        return $user->roles->contains('admin');
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->roles->contains('admin');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->roles->contains('admin');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->roles->contains('admin');
    }
}
