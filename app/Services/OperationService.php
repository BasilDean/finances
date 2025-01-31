<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Operation;
use Illuminate\Pagination\LengthAwarePaginator;

class OperationService
{
    public static function createOperation(Expense|Income $transaction, Account $account, string $transactionType): Operation
    {
        return Operation::create([
            'account_id' => $account->id,
            'amount' => ($transactionType === 'expense' ? -1 : 1) * $transaction->amount,
            'operation_type' => $transactionType,
            'operation_id' => $transaction->id,
            'description' => $transaction->title,
            'balance_after' => $account->amount,
            'performed_at' => $transaction->date,
        ]);
    }

    /**
     * Retrieve paginated operations for an account with search and transformations.
     *
     * @param Account $account
     * @param string|null $search
     * @param int $pageSize
     * @return LengthAwarePaginator
     */

    public function getPaginatedOperations(Account $account, ?string $search, int $pageSize = 20): LengthAwarePaginator
    {
        // Build the query with search filters
        $query = $account->operations()->orderBy('performed_at', 'desc');

        if ($search) {
            $query->where('description', 'like', "%$search%")
                ->orWhere('amount', 'like', "%$search%");
        }

        // Return raw paginated operations
        return $query->paginate($pageSize);
    }

    public function getOperationsStatistics(Account $account, ?array $filter = null): array
    {
        $query = $account->operations()->orderBy('performed_at');

        // Apply filter if provided
        if (!empty($filter['operation_type'])) {
            $query->whereHas('operation_type', function ($q) use ($filter) {
                $q->where('name', $filter['operation_type']);
            });
        }

        $operations = $query->get();

        return collect($operations->reduce(function ($carry, $item) {
            $date = date('d-m-Y', strtotime($item->performed_at));
            $title = ($item->operation_type->name === 'income' ? '+' : '-') .
                $item->amount . ' ' .
                $item->description . ' ' .
                date('H:i:s', strtotime($item->performed_at));

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
            ->map(fn($value, $key) => ['date' => $key, 'data' => $value])
            ->values()
            ->toArray();
    }
}
