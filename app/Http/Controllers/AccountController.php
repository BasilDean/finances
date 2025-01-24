<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\OperationResource;
use App\Models\Account;
use App\Services\OperationService;
use App\Services\SearchService;
use App\Services\UserSettingsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    use AuthorizesRequests;

    private SearchService $searchService;
    private UserSettingsService $userSettingsService;
    private OperationService $operationService;

    public function __construct(SearchService $searchService, UserSettingsService $userSettingsService, OperationService $operationService)
    {
        $this->searchService = $searchService;
        $this->userSettingsService = $userSettingsService;
        $this->operationService = $operationService;

    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Account::class);

        $fields = AccountResource::getFields('show');

        $budget = $this->userSettingsService->getActiveBudget();

        $search = $request->input('search');

        $query = $this->searchService->applySearch(
            $budget->accounts()->orderBy('updated_at', 'desc'),
            $search,
            ['normalized_title', 'amount'] // Fields to search on
        );

        $accounts = $query->paginate(20);

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
        Account::create($validatedData);
        return redirect()->back()->with('status', 'Account created.');
    }

    public function create(): Response
    {
        $this->authorize('create', Account::class);

        $fields = AccountResource::getFields('edit');

        return Inertia::render('Accounts/Create', [
            'status' => session('status'),
            'fields' => $fields
        ]);
    }

    public function show_statistic(Account $account, Request $request): Response
    {
        $this->authorize('viewAny', $account);

        $title = $account->title;
        $slug = $account->slug;

        // Get filtering parameters
        $filter = $request->only(['operation_type']);

        // Fetch filtered statistics
        $operations = $this->operationService->getOperationsStatistics($account, $filter);

        return Inertia::render('Accounts/Stats', [
            'items' => $operations,
            'title' => $title,
            'slug' => $slug,
        ]);
    }

    public function show(Account $account, Request $request): Response
    {
        $this->authorize('view', $account);
        $fields = OperationResource::getFields('show');

        $search = $request->input('search');


        // Get paginated operations from the service
        $operations = $this->operationService->getPaginatedOperations($account, $search);

        // Transform operations using the resource
        $transformedOperations = OperationResource::collection($operations);

        return Inertia::render('Accounts/Show', [
            'status' => session('status'),
            'account' => $account,
            'items' => $transformedOperations,
            'fields' => $fields,
            'filters' => request()->all('search'),
        ]);
    }

    public function edit(Account $account): Response
    {
        $this->authorize('update', $account);

        $fields = AccountResource::getFields('edit');
        return Inertia::render('Accounts/Edit', [
            'account' => $account,
            'fields' => $fields
        ]);
    }

    public function update(AccountRequest $request, Account $account): RedirectResponse
    {
        $this->authorize('update', $account);

        $account->update($request->validated());

        return redirect()->back()->with('status', 'budget updated.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        $this->authorize('delete', $account);

        $account->delete();

        return redirect()->back()->with('status', 'account deleted.');
    }
}
