<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Income;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Account::class);

        $fields = Account::getFields();

        $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->firstOrFail();
        $search = $request->input('search');

        $query = $budget->accounts()->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where('normalized_title', 'like', '%' . $search . '%')->orWhere('amount', 'like', '%' . $search . '%'); // Adjust fields as necessary
        }

        $accounts = $query->paginate(10);

        return Inertia::render('Accounts/Index', [
            'status' => session('status'),
            'accounts' => $accounts,
            'filters' => request()->all('search'),
            'fields' => $fields
        ]);
    }

    public function store(AccountRequest $request): RedirectResponse
    {
        $this->authorize('create', Account::class);
        $validatedData = $request->validated();
        $account = Account::create($validatedData);
        return redirect()->route('accounts.index')->with('status', 'Account created.');
    }

    public function create(): Response
    {
        $this->authorize('create', Account::class);

        $fields = Account::getFields();

        return Inertia::render('Accounts/Create', [
            'status' => session('status'),
            'fields' => $fields
        ]);
    }

    public function show_statistic(Account $account, Request $request): Response
    {
        $title = $account->title;


        $operations = collect($account->operations()->orderBy('performed_at')->get()->reduce(function ($carry, $item) {
            $date = date('d-m-Y', $item->performed_at);
            $title = ($item->operation_type->name === 'income' ? '+' : '-') . $item->amount . ' ' . $item->description . ' ' . date('H:m:s', $item->performed_at);

            if (!isset($carry[$date])) {
                $carry[$date] = [
                    'customInfo' => [$title],
                    'y' => (int)$item->balance_after,
                ];
            } else {
                $carry[$date]['customInfo'][] = $title;
                $carry[$date]['y'] = (int)$item->balance_after;
            }

            return $carry;
        }, []))
            ->map(function ($value, $key) {
                return ['date' => $key, 'data' => $value];
            })
            ->values(); // Convert to indexed array

        $slug = $account->slug;

        return Inertia::render('Accounts/Stats', [
            'items' => $operations,
            'title' => $title,
            'slug' => $slug,
        ]);

    }

    public function show(Account $account, Request $request): Response
    {
        $this->authorize('view', $account);
        $fields = Income::getFields();

        $fetchCount = 50;

        $search = $request->input('search');

        $query1 = $account->expenses();
        $query2 = $account->incomes();

        if ($search) {
            $query1->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
            $query2->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
        }


        $expenses = $query1->take($fetchCount)->get()->map(function ($expense) {
            return [
                'title' => $expense->title,
                'amount' => -1 * $expense->amount,
                'currency' => $expense->currency,
                'created_at' => $expense->created_at->format('H:i d-m-Y'),
                'date' => $expense->date,
                'user' => $expense->user->name,
                'source' => $expense->categories()->pluck('title')->implode(', '),
                'account' => $expense->account->title,
                'slug' => $expense->slug,
                'kind' => 'expense'
            ];
        });
        $incomes = $query2->take($fetchCount)->get()->map(function ($income) {
            return [
                'title' => $income->title,
                'amount' => $income->amount,
                'currency' => $income->currency,
                'created_at' => $income->created_at->format('H:i d-m-Y'),
                'date' => $income->date,
                'user' => $income->user->name,
                'source' => $income->source,
                'account' => $income->account->title,
                'slug' => $income->slug,
                'kind' => 'income'
            ];
        });
        $items = collect([...$expenses, ...$incomes])->sortBy('created_at');

        $totalItems = $items->count();
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $currentItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->values(); // Reset keys

        $paginator = new LengthAwarePaginator(
            $currentItems,
            $totalItems,
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return Inertia::render('Accounts/Show', [
            'status' => session('status'),
            'account' => $account,
            'items' => $paginator,
            'fields' => $fields,
            'filters' => request()->all('search'),
        ]);
    }

    public function edit(Account $account): Response
    {
        $this->authorize('update', $account);

        $fields = Account::getFields();
        return Inertia::render('Accounts/Edit', [
            'account' => $account,
            'fields' => $fields
        ]);
    }

    public function update(AccountRequest $request, Account $account): RedirectResponse
    {
        $this->authorize('update', $account);

        $account->update($request->validated());

        return redirect()->route('accounts.show', $account->slug)->with('status', 'Budget updated.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        $this->authorize('delete', $account);

        $account->delete();

        return redirect()->route('accounts.index')->with('status', 'Account deleted.');
    }
}
