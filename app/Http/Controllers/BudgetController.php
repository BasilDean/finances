<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BudgetController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Budget::class);

        $fields = Budget::getFields();
        $search = $request->input('search');
        $query = Budget::query()->whereRaw('0 = 1');
        if (Auth::check()) {
            $user = Auth::user();
            if ($user) {
                $query = $user->budgets()->orderBy('updated_at', 'desc');
            }
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%'); // Adjust fields as necessary
        }
        $budgets = $query->paginate(10); // Adjust pagination as needed
        return Inertia::render('Budgets/Index', [
            'status' => session('status'),
            'budgets' => $budgets,
            'filters' => request()->all('search'),
            'fields' => $fields
        ]);
    }

    public function edit(Budget $budget): Response
    {
        $this->authorize('update', $budget);
        $fields = Budget::getFields();
        return Inertia::render('Budgets/Edit', [
            'budget' => $budget,
            'fields' => $fields
        ]);
    }

    public function store(BudgetRequest $request): RedirectResponse
    {
        $this->authorize('create', Budget::class);

        $budget = new Budget();
        $budget->fill($request->validated());
        $budget->save();
        $budget->users()->attach(Auth::id());

        return redirect()->route('budgets.show', $budget->slug)->with('status', 'Budget updated.');
    }

    public function show(Budget $budget): Response
    {
        $this->authorize('view', $budget);
        $user = Auth::user();
        if ($user && $user->settings) {
            // Update the active_budget field in settings
            $user->settings->active_budget = $budget->slug;
            $user->settings->save();
        } else {
            $user->settings()->create([
                'active_budget' => $budget->slug,
            ]);
        }

        $accounts = $budget->accounts()
            ->select('accounts.title', 'accounts.slug', 'accounts.amount', 'accounts.currency')->orderBy('accounts.updated_at', 'desc')->take(3)->get();

        $accountIds = $budget->accounts()->pluck('account_id')->toArray();

        $lastIncomes = Income::whereIn('account_id', $accountIds)
            ->select('title', 'slug', 'amount', 'currency')->orderBy('created_at', 'desc')->take(3)->get();

        $lastExpenses = Expense::whereIn('account_id', $accountIds)
            ->select('title', 'slug', 'amount', 'currency')->orderBy('created_at', 'desc')->take(15)->get();

        return Inertia::render('Budgets/Show', [
            'status' => session('status'),
            'budget' => $budget,
            'accounts' => $accounts,
            'incomes' => $lastIncomes,
            'expenses' => $lastExpenses,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Budget::class);
        $fields = Budget::getFields();
        $username = strtolower(Auth::user()->name);

        return Inertia::render('Budgets/Create', [
            'username' => $username,
            'fields' => $fields
        ]);
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        $this->authorize('delete', $budget);

        if ($budget->slug === auth()->user()->settings['active_budget']) {
            auth()->user()->settings()->update(['active_budget' => null]);
        }

        $budget->delete();

        return redirect()->route('budgets.index')->with('status', 'Budget deleted.');
    }

    public function update(BudgetRequest $request, Budget $budget): RedirectResponse
    {
        $this->authorize('update', $budget);

        $budget->update($request->validated());

        return redirect()->route('budgets.show', $budget->slug)->with('status', 'Budget updated.');
    }
}
