<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Operation;
use Illuminate\Support\Facades\Log;

class ExpenseObserver
{
    private const OPERATION_TYPE_EXPENSE = 'expense';

    public function creating(Expense $expense): void
    {
        $expense->title = ucfirst($expense->title);
        $expense->normalized_title = mb_strtolower($expense->title);
        $expense->slug = $this->generateUniqueSlug();
        $expense->date = $expense->date ?? $expense->created_at;
    }

    private function generateUniqueSlug(): string
    {
        $lastExpenseId = Expense::withTrashed()->latest('id')->value('id') ?? 0;
        return (string)($lastExpenseId + 1);
    }

    public function created(Expense $expense): void
    {
        $expenseSnapshot = $expense->only(['title', 'account_id', 'amount']);

        $account = Account::find($expense->account_id);
        $oldAccountSnapshot = $this->snapshotAccount($account);
        $this->adjustAccountAmount($account, -$expense->amount); // Deduct expense amount
        $updatedAccountSnapshot = $this->snapshotAccount($account);

        $operation = $this->createOperation($expense, $account);

        // Log the operation and account updates
        $this->logOperation($expenseSnapshot, $updatedAccountSnapshot, $oldAccountSnapshot, $operation);
    }

    private function snapshotAccount(Account $account): array
    {
        return $account->only(['id', 'title', 'amount']);
    }

    private function adjustAccountAmount(Account $account, float $amountChange): void
    {
        $account->amount = round($account->amount + $amountChange, 2);
        $account->save();
    }

    private function createOperation(Expense $expense, Account $account): Operation
    {
        return Operation::create([
            'account_id' => $expense->account_id,
            'amount' => $expense->amount,
            'operation_type' => self::OPERATION_TYPE_EXPENSE,
            'operation_id' => $expense->id,
            'description' => $expense->title,
            'balance_after' => $account->amount,
            'performed_at' => $expense->date,
        ]);
    }

    private function logOperation(array $expenseSnapshot, array $updatedAccountSnapshot, array $oldAccountSnapshot, Operation $operation): void
    {
        $operationSnapshot = $operation->only(['id', 'account_id', 'amount', 'operation_type', 'description', 'balance_after']);

        Log::channel('custom')->info('Operation Created', [
            'expense' => $expenseSnapshot,
            'account_updated' => $updatedAccountSnapshot,
            'account_old' => $oldAccountSnapshot,
            'operation' => $operationSnapshot,
        ]);
    }

    public function updating(Expense $expense): void
    {
        $expense->normalized_title = mb_strtolower($expense->title);

        $account = Account::find($expense->account_id);
        $this->adjustAccountAmount($account, $expense->getOriginal('amount')); // Revert old amount
    }

    public function updated(Expense $expense): void
    {
        $account = Account::find($expense->account_id);
        $this->adjustAccountAmount($account, -$expense->amount); // Deduct new amount

        $this->updateOperation($expense, $account);
    }

    private function updateOperation(Expense $expense, Account $account): void
    {
        $operation = Operation::where('operation_id', $expense->id)
            ->where('operation_type', self::OPERATION_TYPE_EXPENSE)
            ->first();

        if ($operation) {
            $operation->update([
                'account_id' => $expense->account_id,
                'amount' => $expense->amount,
                'balance_after' => $account->amount,
                'description' => $expense->title,
                'performed_at' => $expense->date,
            ]);
        }
    }

    public function deleting(Expense $expense): void
    {
        $account = Account::find($expense->account_id);
        $this->adjustAccountAmount($account, $expense->amount); // Revert amount

        $operation = Operation::where('operation_id', $expense->id)
            ->where('operation_type', self::OPERATION_TYPE_EXPENSE)
            ->first();

        if ($operation) {
            $operation->delete();
        }
    }
}
