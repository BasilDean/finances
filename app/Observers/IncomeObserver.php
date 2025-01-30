<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Income;
use App\Models\Operation;
use App\Services\AccountService;
use App\Services\OperationService;
use Illuminate\Support\Facades\Log;

class IncomeObserver
{
    private const OPERATION_TYPE_EXPENSE = 'income';
    protected OperationService $operationService;
    protected AccountService $accountService;

    public function __construct(OperationService $operationService, AccountService $accountService)
    {
        $this->operationService = $operationService;
        $this->accountService = $accountService;
    }

    public function creating(Income $income): void
    {
        $income->title = ucfirst($income->title);
        $income->normalized_title = mb_strtolower($income->title);
        $income->slug = $this->generateUniqueSlug();
        $income->date = $income->date ?? $income->created_at;
    }

    private function generateUniqueSlug(): string
    {
        $lastExpenseId = Income::withTrashed()->latest('id')->value('id') ?? 0;
        return (string)($lastExpenseId + 1);
    }

    public function created(Income $income): void
    {
        $incomeSnapshot = $income->only(['title', 'account_id', 'amount']);
        $account = Account::find($income->account_id);

        $oldAccountSnapshot = $this->snapshotAccount($account);
        $this->accountService::adjustBalance($account, $income->amount); // Deduct expense amount
        $updatedAccountSnapshot = $this->snapshotAccount($account);

        $operation = $this->operationService::createOperation($income, $account, self::OPERATION_TYPE_EXPENSE);

        // Log the operation and account updates
        $this->logOperation($incomeSnapshot, $updatedAccountSnapshot, $oldAccountSnapshot, $operation);
    }

    private function snapshotAccount(Account $account): array
    {
        return $account->only(['id', 'title', 'amount']);
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

    public function updating(Income $income): void
    {
        $income->title = ucfirst($income->title);
        $income->normalized_title = mb_strtolower($income->title);

        $account = Account::find($income->account_id);
        $this->accountService::adjustBalance($account, -$income->getOriginal('amount')); // Deduct expense amount
    }

    public function updated(Income $income): void
    {
        $account = Account::find($income->account_id);
        $this->accountService::adjustBalance($account, $income->amount); // Deduct new amount

        $this->updateOperation($income, $account);
    }

    private function updateOperation(Income $income, Account $account): void
    {
        $operation = Operation::where('operation_id', $income->id)
            ->where('operation_type', self::OPERATION_TYPE_EXPENSE)
            ->first();

        if ($operation) {
            $operation->update([
                'account_id' => $income->account_id,
                'amount' => $income->amount,
                'balance_after' => $account->amount,
                'description' => $income->title,
                'performed_at' => $income->date,
            ]);
        }
    }

    public function saving(Income $income): void
    {
    }

    public function saved(Income $income): void
    {
    }

    public function deleting(Income $income): void
    {
        $account = Account::find($income->account_id);
        $this->accountService::adjustBalance($account, -$income->amount); // Revert amount

        $operation = Operation::where('operation_id', $income->id)
            ->where('operation_type', self::OPERATION_TYPE_EXPENSE)
            ->first();

        if ($operation) {
            $operation->delete();
        }
    }

    public function deleted(Income $income): void
    {
    }

    public function restoring(Income $income): void
    {
    }

    public function restored(Income $income): void
    {
    }

    public function forceDeleting(Income $income): void
    {
    }

    public function forceDeleted(Income $income): void
    {
    }
}
