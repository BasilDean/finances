<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;

class PurchaseItemController extends Controller
{
    public function destroy(PurchaseItem $purchaseItem)
    {
        $purchase = $purchaseItem->purchase;

        $purchaseItem->delete();

        $total = $purchase->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $purchase->amount_calculated = $total;
        $purchase->save();

        return redirect()->back();
    }
}
