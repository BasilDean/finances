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
        return true;
        // TODO create actual policies
    }

    public function view(User $user, CurrencyRate $currencyRate): bool
    {
        return true;
        // TODO create actual policies
    }

    public function create(User $user): bool
    {
        return true;
        // TODO create actual policies
    }

    public function update(User $user, CurrencyRate $currencyRate): bool
    {
        return true;
        // TODO create actual policies
    }

    public function delete(User $user, CurrencyRate $currencyRate): bool
    {
        return true;
        // TODO create actual policies
    }

    public function restore(User $user, CurrencyRate $currencyRate): bool
    {
        return true;
        // TODO create actual policies
    }

    public function forceDelete(User $user, CurrencyRate $currencyRate): bool
    {
        return true;
        // TODO create actual policies
    }
}
