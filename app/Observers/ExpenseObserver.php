<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Operation;
use Illuminate\Support\Facades\Log;

class ExpenseObserver
{
    public function creating(Expense $expense): void
    {
        $expense->title = ucfirst($expense->title);
        $expense->normalized_title = mb_strtolower($expense->title);
        $lastIncomeId = Expense::withTrashed()->latest('id')->value('id') ?? 0;
        $expense->slug = $lastIncomeId + 1;
        if (empty($expense->date)) {
            $expense->date = $expense->created_at;
        }
    }

    public function created(Expense $expense): void
    {
        $expenseLog = $expense->only(['title', 'account_id', 'amount']);
        $account_id = $expense->account_id;
        $account = Account::find($account_id);
        $old_account = $account->only(['id', 'title', 'amount']);
        $amount = $expense->amount;
        $account->update(['amount' => round($account->amount - $amount, 2)]);
        $new_account = $account->only(['id', 'title', 'amount']);
        $operation = Operation::create([
            'account_id' => $account_id,
            'amount' => $amount,
            'operation_type' => 'expense',
            'operation_id' => $expense->id,
            'description' => $expense->title,
            'balance_after' => $account->amount,
            'performed_at' => $expense->date,
        ]);
        $operationLog = $operation->only(['id', 'account_id', 'amount', 'operation_type', 'description', 'balance_after']);
        Log::channel('custom')->info('Operation Created',
            [
                'expence' => $expenseLog,
                'account_old' => $new_account,
                'old_account' => $old_account,
                'operation' => $operationLog,
            ]);
    }

    public function updating(Expense $expense): void
    {
        $expense->normalized_title = mb_strtolower($expense->title);
        $account = Account::find($expense->account_id);
        $amount = $expense->getOriginal('amount');
        $account->update(['amount' => round($account->amount + $amount, 2)]);
    }

    public function updated(Expense $expense): void
    {
        $account_id = $expense->account_id;

        $account = Account::find($account_id);
        $amount = $expense->amount;
        $account->amount = round($account->amount - $amount, 2);
        $account->save();

        $operation = Operation::where('operation_id', $expense->id)
            ->where('operation_type', 'expense')
            ->first();
        $operation->update([
            'account_id' => $account_id,
            'amount' => $amount,
            'balance_after' => $account->amount,
            'description' => $expense->title,
            'performed_at' => $expense->date,
        ]);
    }

    public function saving(Expense $expense): void
    {
    }

    public function saved(Expense $expense): void
    {
    }

    public function deleting(Expense $expense): void
    {
        $account = Account::find($expense->account_id);
        $amount = $expense->amount;
        $account->amount = round($account->amount + $amount, 2);
        $account->save();

        $operation = Operation::where('operation_id', $expense->id)
            ->where('operation_type', 'expense')
            ->first();

        $operation->delete();
    }

    public function deleted(Expense $expense): void
    {
    }

    public function restoring(Expense $expense): void
    {
    }

    public function restored(Expense $expense): void
    {
    }

    public function forceDeleting(Expense $expense): void
    {
    }

    public function forceDeleted(Expense $expense): void
    {
    }
}
