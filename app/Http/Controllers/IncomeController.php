<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Models\Account;
use App\Models\Income;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
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

        $search = $request->input('search');

//        $query = Income::with('account')->orderBy('updated_at', 'desc');
        $query = Income::with(['user', 'account'])->orderBy('updated_at', 'desc');
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
                'category' => $income->source,
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

    public function store(IncomeRequest $request): RedirectResponse
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

    public function autocomplete(Request $request): JsonResponse
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
