<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {

        $this->authorize('viewAny', Expense::class);

        $search = $request->input('search');

// Base query for Expenses with type field
        $expenses = Expense::select(
            'expenses.id',
            'expenses.title',
            'expenses.slug',
            'expenses.amount',
            'expenses.currency',
            'expenses.created_at',
            DB::raw("'expense' as type"),
            'expenses.user_id', // Include user_id for lazy-loading
            'expenses.account_id' // Include account_id for lazy-loading
        )
            ->when($search, function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });

// Base query for Purchases with type field
        $purchases = Purchase::select(
            'purchases.id',
            'purchases.title',
            'purchases.slug',
            'purchases.amount',
            'purchases.currency',
            'purchases.created_at',
            DB::raw("'purchase' as type"),
            'purchases.user_id', // Include user_id for lazy-loading
            'purchases.account_id' // Include account_id for lazy-loading
        )
            ->when($search, function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });

// Combine the queries with union all and sort
        $combined = $expenses->unionAll($purchases)
            ->orderBy('created_at', 'desc'); // Sort by most recent

// Paginate the combined query
        $paginated = DB::table(DB::raw("({$combined->toSql()}) as combined"))
            ->mergeBindings($combined->getQuery())
            ->paginate(20);

// Transform the results for the view
        $paginated->getCollection()->transform(function ($record) {
            $user = User::find($record->user_id)->name; // Lazy load user
            $account = Account::find($record->account_id)->title; // Lazy load account
            $categories = $record->type === 'expense'
                ? Expense::find($record->id)->categories()->pluck('title')->implode(', ')
                : Purchase::find($record->id)->categories()->pluck('title')->implode(', ');
            return [
                'id' => $record->id,
                'title' => $record->title,
                'slug' => $record->slug,
                'amount' => $record->amount,
                'currency' => $record->currency,
                'created_at' => date('H:i d-m-Y', strtotime($record->created_at)),
                'source' => $categories,
                'user' => $user, // Populate user's name dynamically if needed
                'account' => $account, // Populate account's title dynamically if needed
                'kind' => $record->type, // 'expense' or 'purchase'
            ];
        });

// Pass the transformed data to Inertia
        $fields = Expense::getFields();

        return Inertia::render('Expenses/Index', [
            'expenses' => $paginated,
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

        $fields = Expense::getFields();
        return Inertia::render('Expenses/Create', [
            'fields' => $fields,
            'resetFields' => ['title', 'amount'],
        ]);
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


        $expense->user_id = $request->user['id'];
        $expense->account_id = $request->account['id'];
        $expense->save();

        return redirect()->route('expense.index', $expense->slug)->with('status', 'Expense updated.');
    }

    public function destroy(Expense $Expense): RedirectResponse
    {
        $this->authorize('delete', $Expense);

        $Expense->delete();

        return redirect()->route('expense.index')->with('status', 'Expense deleted.');
    }
}
