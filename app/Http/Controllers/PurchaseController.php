<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Account;
use App\Models\Purchase;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseController extends Controller
{
    use AuthorizesRequests;

    public function create(): Response
    {
        $this->authorize('create', Purchase::class);

        $fields = Purchase::getFields();
        return Inertia::render('Purchases/Create', [
            'fields' => $fields,
        ]);
    }

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

        return Inertia::render('Purchases/Show', [
            'purchase' => $purchase
        ])->with('success', 'Purchase was successfully created.');

    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $this->authorize('delete', $purchase);

        $purchase->delete();

        return redirect()->route('expense.index')->with('status', 'Expense deleted.');
    }
}
