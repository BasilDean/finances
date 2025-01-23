<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\PurchaseItem;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Response
    {

        $this->authorize('viewAny', Expense::class);


        $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->firstOrFail();
        $accounts = $budget->accounts->pluck('id')->toArray();


        $search = $request->input('search');
        $period = $request->input('period');
        $range = $request->input('range');


        $query = Expense::with(['user', 'account'])->whereIn('account_id', $accounts);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
        }
        if (!$period || $period === 'week') {
            $startOfWeek = auth()->user()->settings['start_of_the_week'] ?? 'monday';


// Map day names to Carbon constants
            $daysOfWeek = [
                'sunday' => Carbon::SUNDAY,
                'monday' => Carbon::MONDAY,
                'tuesday' => Carbon::TUESDAY,
                'wednesday' => Carbon::WEDNESDAY,
                'thursday' => Carbon::THURSDAY,
                'friday' => Carbon::FRIDAY,
                'saturday' => Carbon::SATURDAY,
            ];

// Determine the start date from the user's preference
            $startDate = Carbon::now()->startOfWeek($daysOfWeek[$startOfWeek]);

// Determine the end date (7 days after the start of the week)
            $endDate = $startDate->copy()->addDays(6);

            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($period === 'month') {
            $query->whereMonth('date', Carbon::now()->month);
        } elseif ($period === 'year') {
            $query->whereYear('date', Carbon::now()->year);
        } elseif ($period === 'custom') {
            if ($range) {
//                $range = explode(' - ', $range);
                $startDate = Carbon::parse($range[0]);
                $endDate = Carbon::parse($range[1]);
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }
        $query->orderBy('date', 'desc');


        $expenses = $query->paginate(20)->appends($request->all());
        $expenses->getCollection()->transform(function ($expense) {
            return [
                'id' => $expense->id,
                'title' => $expense->title,
                'slug' => $expense->slug,
                'amount' => $expense->amount,
                'currency' => $expense->currency,
                'has_items' => (bool)$expense->has_items,
                'created_at' => $expense->created_at->format('H:i d-m-Y'),
                'date' => $expense->date->format('Y-m-d H:i:s'),
                'source' => $expense->categories()->pluck('title')->implode(', '),
                'user' => $expense->user->name ?? null, // Extract user's name
                'account' => $expense->account->title ?? null, // Extract account's title
            ];
        });
        $fields = Expense::getFields();


        return Inertia::render('Expenses/Index', [
            'expenses' => $expenses,
            'fields' => $fields,
            'filters' => request()->all(),
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
        $expense->has_items = (bool)$request->has_items;

        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $expense->date = $formattedDate;
        $expense->save();

        $expense->categories()->sync($request->source['id']);

        $account->amount -= $request->amount;
        $account->save();

        if ($expense->has_items) {
            return redirect()->route('expense.edit', $expense->slug)->with('status', 'Expense created.');
        }

        $fields = Expense::getFields();
        return Inertia::render('Expenses/Create', [
            'fields' => $fields,
            'resetFields' => ['title', 'amount'],
        ]);
    }

    public function show(Expense $Expense): Expense
    {
        $this->authorize('view', $Expense);

        return $Expense;
    }

    public function edit(Expense $expense): Response
    {
        $this->authorize('update', $expense);

        $fields = Expense::getFields();
        $itemFields = PurchaseItem::getFields();

        $expenseData = [
            'id' => $expense->id,
            'title' => $expense->title,
            'slug' => $expense->slug,
            'amount' => $expense->amount,
            'currency' => $expense->currency,
            'created_at' => $expense->created_at->format('H:i d-m-Y'),
            'date' => $expense->date,
            'source' => $expense->categories()->first(),
            'has_items' => (bool)$expense->has_items,
            'user' => $expense->user ?? null, // Extract user's name
            'account' => $expense->account ?? null, // Extract account's title
            'amount_calculated' => $expense->amount_calculated,
        ];
        if ($expenseData['has_items']) {

            $items = $expense->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'purchase_id' => $item->expense_id,
                    'title' => $item->title,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            });
            $expenseData['items'] = $items;
        }
        return Inertia::render('Expenses/Edit', [
            'expense' => $expenseData,
            'fields' => $fields,
            'itemFields' => $itemFields,
        ]);
    }

    public function createOrUpdateItems(Request $request, Expense $expense): RedirectResponse
    {
        $items = $request->localItems;
        foreach ($items as $item) {
            if ($item['id']) {
                $expenseItem = PurchaseItem::findOrFail($item['id']);
                $expenseItem->update($item);
                $expenseItem->title = ucfirst($expenseItem->title);
                $expenseItem->save();
            } else {
                $item['title'] = ucfirst($item['title']);
                $expense->items()->create($item);
                $expense->save();
            }
        }
        $total = $expense->items->sum(function ($item) {
            return round($item->price * $item->quantity, 2);
        });
        $expense->amount_calculated = $total;
        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $expense->date = $formattedDate;
        $expense->save();
        return redirect()->back()->with('success', 'success');
    }

    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {

        $this->authorize('update', $expense);

        $validatedData = $request->validated();
        $filteredData = Arr::except($validatedData, ['has_items', 'date']);
        $expense->update($filteredData);

        $expense->currency = $request->account['currency'];
        $expense->user_id = $request->user['id'];
        $expense->account_id = $request->account['id'];

        $expense->has_items = (bool)$request->has_items;

        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $expense->date = $formattedDate;
        $expense->save();

        return redirect()->route('expense.index', $expense->slug)->with('status', 'Expense updated.');
    }

    public function create(): Response
    {
        $this->authorize('create', Expense::class);

        $fields = Expense::getFields();
        return Inertia::render('Expenses/Create', [
            'fields' => $fields,
        ]);
    }

    public function destroy(Expense $Expense): RedirectResponse
    {
        $this->authorize('delete', $Expense);

        $Expense->delete();

        return redirect()->route('expense.index')->with('status', 'Expense deleted.');
    }
}
