<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Models\Budget;
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

        $fillableFields = (new Budget())->getFillable();
        $search = $request->input('search');
        $query = Auth::user()->budgets()->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%'); // Adjust fields as necessary
        }

        $budgets = $query->paginate(10); // Adjust pagination as needed

        return Inertia::render('Budgets/Index', [
            'status' => session('status'),
            'budgets' => $budgets,
            'filters' => request()->all('search'),
            'fillableFields' => $fillableFields
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Budget::class);
        $fillableFields = (new Budget())->getFillable();
        $fields = config('fields');

        $username = strtolower(Auth::user()->name);

        return Inertia::render('Budgets/Create', [
            'username' => $username,
            'fillableFields' => $fillableFields,
            'fields' => $fields
        ]);
    }

    public function edit(Budget $budget): Response
    {
        $this->authorize('update', $budget);
        $fillableFields = (new Budget())->getFillable();
        $fields = config('fields');
        return Inertia::render('Budgets/Edit', [
            'budget' => $budget,
            'fillableFields' => $fillableFields,
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

        session(['default_budget' => $budget->slug]);

        $accounts = $budget->accounts()->orderBy('created_at', 'desc')->take(5)->get();
        $total = $budget->getBudgetTotal();

        $incomes = $budget->accounts()
            ->with('incomes') // Assuming accounts have a relationship called 'incomes'
            ->get()
            ->pluck('incomes')
            ->flatten()
            ->sortByDesc('created_at')
            ->take(10);

        $expenses = $budget->accounts()
            ->with('expenses') // Assuming accounts have a relationship called 'incomes'
            ->get()
            ->pluck('expenses')
            ->flatten()
            ->sortByDesc('created_at')
            ->take(10);

        return Inertia::render('Budgets/Show', [
            'status' => session('status'),
            'budget' => $budget,
            'accounts' => $accounts,
            'total' => $total,
            'incomes' => $incomes,
            'expenses' => $expenses,
        ]);
    }

    public function update(BudgetRequest $request, Budget $budget): RedirectResponse
    {
        $this->authorize('update', $budget);

        $budget->update($request->validated());

        return redirect()->route('budgets.show', $budget->slug)->with('status', 'Budget updated.');
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgets.index')->with('status', 'Budget deleted.');
    }
}
