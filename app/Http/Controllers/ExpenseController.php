<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Account;
use App\Models\Expense;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {

        $this->authorize('viewAny', Expense::class);

        $search = $request->input('search');

        $query = Expense::with(['user', 'account'])->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
        }

        $expenses = $query->paginate(20);

        $expenses->getCollection()->transform(function ($expense) {
            return [
                'id' => $expense->id,
                'title' => $expense->title,
                'slug' => $expense->slug,
                'amount' => $expense->amount,
                'currency' => $expense->currency,
                'created_at' => $expense->created_at->format('H:i d-m-Y'),
                'source' => $expense->categories()->pluck('title')->implode(', '),
                'user' => $expense->user->name ?? null, // Extract user's name
                'account' => $expense->account->title ?? null, // Extract account's title
            ];
        });
        $fields = Expense::getFields();
        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'fields' => $fields,
            'filters' => request()->all('search'),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Expense::class);

        $fields = Expense::getFields();
        return Inertia::render('Expenses/Create', [
            'fields' => $fields,
        ]);
    }

    public function store(ExpenseRequest $request)
    {
        $this->authorize('create', Expense::class);

        $account_id = $request->account['id'];
        $account = Account::findOrFail($account_id);
        $currency = $account->currency;

        $user_id = Auth::id();

        $expense = new Expense();
        $expense->fill($request->validated());
        $expense->user_id = $user_id;
        $expense->currency = $currency;
        $expense->account_id = $account_id;
        $expense->save();

        $expense->categories()->sync($request->source['id']);

        $account->amount -= $request->amount;
        $account->save();

        return redirect()->route('expense.create')->with('success', 'expense created.');
    }

    public function show(Expense $Expense)
    {
        $this->authorize('view', $Expense);

        return $Expense;
    }

    public function edit(Expense $expense): Response
    {
        $this->authorize('update', $expense);

        $fields = Expense::getFields();

        $expenseData = [
            'id' => $expense->id,
            'title' => $expense->title,
            'slug' => $expense->slug,
            'amount' => $expense->amount,
            'currency' => $expense->currency,
            'created_at' => $expense->created_at->format('H:i d-m-Y'),
            'source' => $expense->categories()->first(),
            'user' => $expense->user ?? null, // Extract user's name
            'account' => $expense->account ?? null, // Extract account's title
        ];
        return Inertia::render('Expenses/Edit', [
            'expense' => $expenseData,
            'fields' => $fields,
        ]);
    }


    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {

        $this->authorize('update', $expense);

        $expense->update($request->validated());

        return redirect()->route('expense.edit', $expense->slug)->with('status', 'Expense updated.');
    }

    public function destroy(Expense $Expense): RedirectResponse
    {
        $this->authorize('delete', $Expense);

        $Expense->delete();

        return redirect()->route('expense.index')->with('status', 'Expense deleted.');
    }
}
