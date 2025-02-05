<?php

namespace App\Policies;

use App\Models\CurrencyRate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyRatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->roles->contains('admin');
    }

    public function view(User $user, CurrencyRate $currencyRate): bool
    {
        return $user->roles->contains('admin');
    }

    public function create(User $user): bool
    {
        return $user->roles->contains('admin');
    }

    public function update(User $user, CurrencyRate $currencyRate): bool
    {
        return $user->roles->contains('admin');
    }

    public function delete(User $user, CurrencyRate $currencyRate): bool
    {
        return $user->roles->contains('admin');
    }

    public function restore(User $user, CurrencyRate $currencyRate): bool
    {
        return $user->roles->contains('admin');
    }

    public function forceDelete(User $user, CurrencyRate $currencyRate): bool
    {
        return $user->roles->contains('admin');
    }
}
