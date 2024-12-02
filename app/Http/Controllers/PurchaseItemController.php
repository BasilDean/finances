<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseItemRequest;
use App\Models\PurchaseItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PurchaseItemController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', PurchaseItem::class);

        return PurchaseItem::all();
    }

    public function store(PurchaseItemRequest $request)
    {
        $this->authorize('create', PurchaseItem::class);

        return PurchaseItem::create($request->validated());
    }

    public function show(PurchaseItem $purchaseItem)
    {
        $this->authorize('view', $purchaseItem);

        return $purchaseItem;
    }

    public function update(PurchaseItemRequest $request, PurchaseItem $purchaseItem)
    {
        $this->authorize('update', $purchaseItem);

        $purchaseItem->update($request->validated());

        return $purchaseItem;
    }

    public function destroy(PurchaseItem $purchaseItem)
    {
        $this->authorize('delete', $purchaseItem);

        $purchaseItem->delete();

        return response()->json();
    }
}
