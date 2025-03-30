<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetRequest;
use App\Http\Resources\BudgetResource;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use App\Services\OperationService;
use App\Services\SearchService;
use App\Services\UserSettingsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BudgetController extends Controller
{
    use AuthorizesRequests;

    private SearchService $searchService;
    private UserSettingsService $userSettingsService;

    public function __construct(SearchService $searchService, UserSettingsService $userSettingsService, OperationService $operationService)
    {
        $this->searchService = $searchService;
        $this->userSettingsService = $userSettingsService;

    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Budget::class);

        $fields = BudgetResource::getFields('show');
        $search = $request->input('search');


        $user = Auth::user();
        $query = $this->searchService->applySearch(
            $user->budgets()->orderBy('updated_at', 'desc'),
            $search,
            ['normalized_title', 'balance'] // Fields to search on
        );
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
        $fields = BudgetResource::getFields('edit');
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
        $this->userSettingsService->setActiveBudget($user, $budget->slug);

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
        $fields = BudgetResource::getFields('edit');
        $username = strtolower(Auth::user()->name);

        return Inertia::render('Budgets/Create', [
            'username' => $username,
            'fields' => $fields
        ]);
    }

    public function show_statistic(Budget $budget, Request $request): Response
    {
        $title = $budget->title;


        $operations = collect($budget->history()->orderBy('performed_at')->take(20)->reduce(function ($carry, $item) {
            $date = date('d-m-Y', (int)$item->performed_at);
            $title = ($item->operation_type === 'income' ? '+' : '-') . $item->amount . ' ' . $item->description . ' ' . date('H:m:s', (int)$item->performed_at);

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

        $slug = $budget->slug;

        return Inertia::render('Accounts/Stats', [
            'items' => $operations,
            'title' => $title,
            'slug' => $slug,
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
