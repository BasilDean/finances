<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseController extends Controller
{
    use AuthorizesRequests;

    public function store(PurchaseRequest $request): Response
    {
        $this->authorize('create', Purchase::class);

        $account_id = $request->account['id'];
        $currency = Account::findOrFail($account_id)->currency;

        $user_id = Auth::id();

        $purchase = new Purchase();
        $purchase->fill($request->validated());
        $purchase->user_id = $user_id;
        $purchase->currency = $currency;
        $purchase->account_id = $account_id;
        $purchase->save();

        $purchase->categories()->sync($request->source['id']);

        $fields = Purchase::getFields();
        $itemFields = PurchaseItem::getFields();
        $items = $purchase->items->map(function ($item) {
            return [
                'id' => $item->id,
                'purchase_id' => $item->purchase_id,
                'title' => $item->title,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        });

        $purchaseData = [
            'id' => $purchase->id,
            'title' => $purchase->title,
            'slug' => $purchase->slug,
            'amount' => $purchase->amount,
            'currency' => $purchase->currency,
            'created_at' => $purchase->created_at->format('H:i d-m-Y'),
            'source' => $purchase->categories()->first(),
            'user' => $purchase->user ?? null, // Extract user's name
            'account' => $purchase->account ?? null, // Extract account's title
            'items' => $items,
        ];

        return Inertia::render('Purchases/Edit', [
            'purchase' => $purchaseData,
            'fields' => $fields,
            'itemFields' => $itemFields,
        ])->with('success', 'Purchase was successfully created.');

    }

    public function edit(Purchase $purchase)
    {
        $this->authorize('update', $purchase);

        $fields = Purchase::getFields();
        $itemFields = PurchaseItem::getFields();

        $items = $purchase->items->map(function ($item) {
            return [
                'id' => $item->id,
                'purchase_id' => $item->purchase_id,
                'title' => $item->title,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        });

        $purchaseData = [
            'id' => $purchase->id,
            'title' => $purchase->title,
            'slug' => $purchase->slug,
            'amount' => $purchase->amount,
            'total_calculated' => $purchase->amount_calculated,
            'currency' => $purchase->currency,
            'created_at' => $purchase->created_at->format('H:i d-m-Y'),
            'source' => $purchase->categories()->first(),
            'user' => $purchase->user ?? null, // Extract user's name
            'account' => $purchase->account ?? null, // Extract account's title
            'items' => $items,
        ];
        return Inertia::render('Purchases/Edit', [
            'purchase' => $purchaseData,
            'fields' => $fields,
            'itemFields' => $itemFields,
        ]);

    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $this->authorize('delete', $purchase);

        $purchase->delete();

        return redirect()->route('expense.index')->with('status', 'Expense deleted.');
    }

    public function createOrUpdateItems(Request $request, Purchase $purchase)
    {
        $items = $request->localItems;
        foreach ($items as $item) {
            if ($item['id']) {
                $purchaseItem = PurchaseItem::findOrFail($item['id']);
                $purchaseItem->update($item);
                $purchaseItem->save();
            } else {
                $purchase->items()->create($item);
                $purchase->save();
            }
        }
        $total = $purchase->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $purchase->amount_calculated = $total;
        $purchase->save();
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
    }

    public function create(): Response
    {
        $this->authorize('create', Purchase::class);

        $fields = Purchase::getFields();
        return Inertia::render('Purchases/Create', [
            'fields' => $fields,
        ]);
    }
}
