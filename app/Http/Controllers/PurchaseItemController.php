<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;
use Illuminate\Http\RedirectResponse;

class PurchaseItemController extends Controller
{
    public function destroy(PurchaseItem $purchaseItem): RedirectResponse
    {
        $purchase = $purchaseItem->expense;

        $purchaseItem->delete();

        $total = $purchase->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $purchase->amount_calculated = $total;
        $purchase->save();

        return redirect()->back();
    }
}
