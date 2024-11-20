<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
        // TODO create actual policies
    }

    public function view(User $user, Setting $setting): bool
    {
        return true;
        // TODO create actual policies
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create actual policies
    }

    public function update(User $user, Setting $setting): bool
    {
        return true;
        // TODO create actual policies
    }

    public function delete(User $user, Setting $setting): bool
    {
        return true;
        // TODO create actual policies
    }

    public function restore(User $user, Setting $setting): bool
    {
        return true;
        // TODO create actual policies
    }

    public function forceDelete(User $user, Setting $setting): bool
    {
        return true;
        // TODO create actual policies
    }
}
