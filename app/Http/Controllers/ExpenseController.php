<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {

        $this->authorize('viewAny', Expense::class);
        $expenses = Expense::with('account')->with('user')->with('categories')->orderBy('created_at', 'desc')->paginate(20);
        $budget = Budget::where('slug', session()->get('default_budget'))->first();
        $accounts = $budget->accounts()->get();
        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'accounts' => $accounts,
        ]);
    }

    public function store(ExpenseRequest $request)
    {
        $this->authorize('create', Expense::class);
        $account = Account::findOrFail($request->account_id);
        $currency = $account->currency;

        $categoryTitle = $request->category;


        $category = Category::where('title', $categoryTitle)->firstOrCreate();
        $category->usage_count += 1;
        $category->save();

        $user_id = Auth::id();

        $expense = new Expense();
        $expense->fill($request->validated());
        $expense->user_id = $user_id;
        $expense->currency = $currency;
        $expense->account()->associate($account);
        $expense->save();

        $account->amount -= $request->amount;
        $account->save();

        $expense->categories()->attach($category);


        return redirect()->route('expense.index')->with('success', 'expense created.');
    }

    public function show(Expense $Expense)
    {
        $this->authorize('view', $Expense);

        return $Expense;
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {

        $this->authorize('update', $expense);

        $expense->update($request->validated());

        return $expense;
    }

    public function destroy(Expense $Expense)
    {
        $this->authorize('delete', $Expense);

        $Expense->delete();

        return response()->json();
    }

    public function autocompleteTitle(Request $request)
    {
        $query = $request->get('query');

        if (strlen($query) < 3) {
            return response()->json([]);
        }
        $normalizedQuery = mb_strtolower($query);
        $results = Expense::where('normalized_title', 'LIKE', "%{$normalizedQuery}%")
            ->limit('5')
            ->pluck('title')
            ->unique() // Ensure unique titles
            ->filter(function ($title) use ($query) {
                return mb_strtolower($title) !== mb_strtolower($query);
            })
            ->values(); // Reset keys after filtering;
        return response()->json($results);
    }

    public function autocompleteCategory(Request $request)
    {
        $query = $request->get('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }
        $normalizedQuery = mb_strtolower($query);
        $results = Category::where('normalized_title', 'LIKE', "%{$normalizedQuery}%")
            ->limit('5')
            ->pluck('title')
            ->unique() // Ensure unique titles
            ->filter(function ($title) use ($query) {
                return mb_strtolower($title) !== mb_strtolower($query);
            })
            ->values(); // Reset keys after filtering;
        return response()->json($results);
    }
}
