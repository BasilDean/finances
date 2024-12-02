<?php

namespace App\Policies;

use App\Models\PurchaseItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseItemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {

    }

    public function view(User $user, PurchaseItem $purchaseItem): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, PurchaseItem $purchaseItem): bool
    {
    }

    public function delete(User $user, PurchaseItem $purchaseItem): bool
    {
    }

    public function restore(User $user, PurchaseItem $purchaseItem): bool
    {
    }

    public function forceDelete(User $user, PurchaseItem $purchaseItem): bool
    {
    }
}
