<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    public function view(User $user, Payment $payment): bool
    {
        return $payment->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Payment $payment): bool
    {
        return $payment->user_id === $user->id;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $payment->user_id === $user->id;
    }

    public function restore(User $user, Payment $payment): bool
    {
        return $payment->user_id === $user->id;
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return $payment->user_id === $user->id;
    }
}
