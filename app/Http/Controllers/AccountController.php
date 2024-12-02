<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Budget;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Account::class);


        $fillableFields = (new Account())->getFillable();

        $budget = Budget::where('slug', session()->get('default_budget'))->firstOrFail();
        $search = $request->input('search');

        $query = $budget->accounts()->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%'); // Adjust fields as necessary
        }

        $accounts = $query->paginate(10);

        return Inertia::render('Accounts/Index', [
            'status' => session('status'),
            'accounts' => $accounts,
            'filters' => request()->all('search'),
            'fillableFields' => $fillableFields
        ]);
    }

    public function store(AccountRequest $request)
    {
        $this->authorize('create', Account::class);
        $validatedData = $request->validated();
        $account = Account::create($validatedData);
        return redirect()->route('accounts.index')->with('status', 'Account created.');
    }

    public function create()
    {
        $this->authorize('create', Account::class);

        $fillableFields = (new Account())->getFillable();
        $fields = config('fields');

        return Inertia::render('Accounts/Create', [
            'status' => session('status'),
            'fillableFields' => $fillableFields,
            'fields' => $fields
        ]);
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);

        $budgets = $account->budgets()->get();

        return Inertia::render('Accounts/Show', [
            'status' => session('status'),
            'budgets' => $budgets,
            'account' => $account,
        ]);
    }

    public function edit(Account $account): Response
    {
        $this->authorize('update', $account);
        $fillableFields = (new Account())->getFillable();
        $fields = config('fields');
        return Inertia::render('Accounts/Edit', [
            'account' => $account,
            'fillableFields' => $fillableFields,
            'fields' => $fields
        ]);
    }

    public function update(AccountRequest $request, Account $account): RedirectResponse
    {
        $this->authorize('update', $account);

        $account->update($request->validated());

        return redirect()->route('accounts.show', $account->slug)->with('status', 'Budget updated.');
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $account->delete();

        return redirect()->route('accounts.index')->with('status', 'Account deleted.');
    }
}
