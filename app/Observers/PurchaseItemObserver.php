<?php

namespace App\Observers;

use App\Models\PurchaseItem;

class PurchaseItemObserver
{
    public function creating(PurchaseItem $purchaseItem): void
    {
        $purchaseItem->normalized_title = mb_strtolower($purchaseItem->title);
    }

    public function updating(PurchaseItem $purchaseItem): void
    {
        $purchaseItem->normalized_title = mb_strtolower($purchaseItem->title);
    }
}
