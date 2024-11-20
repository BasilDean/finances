<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Expense::class);

        return Expense::all();
    }

    public function store(ExpenseRequest $request)
    {
        $this->authorize('create', Expense::class);

        return Expense::create($request->validated());
    }

    public function show(Expense $Expense)
    {
        $this->authorize('view', $Expense);

        return $Expense;
    }

    public function update(ExpenseRequest $request, Expense $Expense)
    {
        $this->authorize('update', $Expense);

        $Expense->update($request->validated());

        return $Expense;
    }

    public function destroy(Expense $Expense)
    {
        $this->authorize('delete', $Expense);

        $Expense->delete();

        return response()->json();
    }
}
