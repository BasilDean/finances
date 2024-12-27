<?php

namespace App\Http\Controllers;

use App\Models\PurchaseItem;

class PurchaseItemController extends Controller
{
    public function destroy(PurchaseItem $purchaseItem)
    {
        $purchaseItem->delete();

        return redirect()->back();
    }
}
