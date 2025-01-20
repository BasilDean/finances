<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class IncomeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Income::class);

        $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->firstOrFail();
        $accounts = $budget->accounts->pluck('id')->toArray();

        $search = $request->input('search');

        $query = Income::with(['user', 'account'])->whereIn('account_id', $accounts)->orderBy('created_at', 'desc');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
        }

        $incomes = $query->paginate(20);
        $incomes->getCollection()->transform(function ($income) {
            return [
                'id' => $income->id,
                'title' => $income->title,
                'slug' => $income->slug,
                'amount' => $income->amount,
                'currency' => $income->currency,
                'created_at' => $income->created_at->format('H:i d-m-Y'),
                'updated_at' => $income->updated_at->format('H:i d-m-Y'),
                'date' => $income->date->format('Y-m-d H:i:s'),
                'source' => $income->source,
                'user' => $income->user->name ?? null, // Extract user's name
                'account' => $income->account->title ?? null, // Extract account's title
            ];
        });
        $fields = Income::getFields();
        return Inertia::render('Incomes/Index', [
            'incomes' => $incomes,
            'fields' => $fields,
            'filters' => request()->all('search'),
        ]);
    }

    public function create(Request $request): Response
    {
        $this->authorize('create', Income::class);

        $fields = Income::getFields();
        return Inertia::render('Incomes/Create', [
            'fields' => $fields,
        ]);
    }

    public function store(IncomeRequest $request): RedirectResponse
    {
        $this->authorize('create', Income::class);

        $account_id = $request->account['id'];
        $account = Account::findOrFail($account_id);
        $currency = $account->currency;

        $user_id = Auth::id();

        $income = new Income();
        $income->fill($request->validated());
        $income->user_id = $user_id;
        $income->currency = $currency;
        $income->account_id = $account_id;
        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $income->date = $formattedDate;
        $income->save();

        $account->amount += $request->amount;
        $account->save();

        return redirect()->route('income.index')->with('success', 'Income created.');
    }

    public function show(Income $income): Income
    {
        $this->authorize('view', $income);

        return $income;
    }

    public function edit(Income $income): Response
    {
        $this->authorize('update', $income);

        $fields = Income::getFields();

        $incomeData = [
            'id' => $income->id,
            'title' => $income->title,
            'slug' => $income->slug,
            'amount' => $income->amount,
            'currency' => $income->currency,
            'created_at' => $income->created_at->format('H:i d-m-Y'),
            'date' => $income->date,
            'source' => $income->source,
            'user' => $income->user ?? null, // Extract user's name
            'account' => $income->account ?? null, // Extract account's title
        ];
        return Inertia::render('Incomes/Edit', [
            'income' => $incomeData,
            'fields' => $fields,
        ]);
    }

    public function update(IncomeRequest $request, Income $income): RedirectResponse
    {
        $this->authorize('update', $income);

        $income->update($request->validated());

        $income->account_id = $request->account['id'];

        $income->user_id = $request->user['id'];

        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $income->date = $formattedDate;

        $income->save();

        return redirect()->route('income.index')->with('status', 'Income updated.');
    }

    public function destroy(Income $income): RedirectResponse
    {
        $this->authorize('delete', $income);

        $income->delete();

        return redirect()->route('income.index')->with('status', 'Income deleted.');
    }
}
