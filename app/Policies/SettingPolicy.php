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
        return $user->roles->contains('admin');
    }

    public function view(User $user, Setting $setting): bool
    {
        return $setting->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Setting $setting): bool
    {
        return $setting->user_id === $user->id;
    }

    public function delete(User $user, Setting $setting): bool
    {
        return $setting->user_id === $user->id;
    }

    public function restore(User $user, Setting $setting): bool
    {
        return $user->roles->contains('admin');
    }

    public function forceDelete(User $user, Setting $setting): bool
    {
        return $user->roles->contains('admin');
    }
}
