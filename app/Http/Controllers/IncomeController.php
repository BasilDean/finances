<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Income;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    use AuthorizesRequests;

    public function index(): \Inertia\Response
    {
        $this->authorize('viewAny', Income::class);
        $incomes = Income::with('account')->orderBy('created_at', 'desc')->paginate(20);
        $budget = Budget::where('slug', session()->get('default_budget'))->first();
        $accounts = $budget->accounts()->get();
//        dd($incomes);
        return Inertia::render('Incomes/Index', [
            'incomes' => $incomes,
            'accounts' => $accounts,
        ]);
    }

    public function store(IncomeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('create', Income::class);

        $account = Account::findOrFail($request->account_id);
        $currency = $account->currency;

        $user_id = Auth::id();

        $income = new Income();
        $income->fill($request->validated());
        $income->user_id = $user_id;
        $income->currency = $currency;
        $income->save();

        $account->amount += $request->amount;
        $account->save();

        return redirect()->route('income.index')->with('success', 'Income created.');
    }

    public function show(Income $income)
    {
        $this->authorize('view', $income);

        return $income;
    }

    public function update(IncomeRequest $request, Income $income)
    {
        $this->authorize('update', $income);

        $income->update($request->validated());

        return $income;
    }

    public function destroy(Income $income)
    {
        $this->authorize('delete', $income);

        $income->delete();

        return response()->json();
    }

    public function autocomplete(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->get('query');

        if (strlen($query) < 3) {
            return response()->json([]);
        }
        $normalizedQuery = mb_strtolower($query);
        $results = Income::where('normalized_title', 'LIKE', "%{$normalizedQuery}%")
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
