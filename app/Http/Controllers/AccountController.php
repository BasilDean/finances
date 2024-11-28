<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Budget;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Account::class);


        $fillableFields = (new Budget())->getFillable();

        $budget = Budget::where('slug', session()->get('default_budget'))->firstOrFail();
        $accounts = $budget->accounts()->paginate(10);

        return Inertia::render('Accounts/Index', [
            'status' => session('status'),
            'accounts' => $accounts,
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

        return Inertia::render('Accounts/Create', [
            'status' => session('status'),
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

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        $account->delete();

        return redirect()->route('accounts.index')->with('status', 'Account deleted.');
    }
}
