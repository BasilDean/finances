<?php

namespace App\Observers;

use App\Models\Budget;
use App\Models\BudgetHistory;
use App\Services\BudgetService;
use Illuminate\Support\Str;

class BudgetObserver
{
    public function creating(Budget $budget): void
    {

        $slug = Str::slug($budget->title);
        $originalSlug = $slug;
        $counter = 1;

        // Ensure the slug is unique by including soft deleted items
        while (Budget::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $budget->slug = $slug;
    }

    public function created(Budget $budget): void
    {
    }

    public function saving(Budget $budget): void
    {

    }

    public function saved(Budget $budget)
    {

    }

    public function updating(Budget $budget): void
    {
    }

    public function updated(Budget $budget): void
    {
        $budgetService = new BudgetService();
        $total = $budgetService->CalculateBudgetTotal($budget);
        if ($budget->balance !== $total) {
            $budgetService->updateBudgetTotal($budget, $total);
            $diff = $budget->balance - $total;
            BudgetHistory::create([
                'budget_id' => $budget->id,
                'amount' => $diff,
                'operation_type' => $diff > 0 ? 'expense' : 'income',
                'description' => 'Budget balance changed',
                'balance_after' => $total,
                'performed_at' => now(),
            ]);
        }
    }

    public function deleting(Budget $budget): void
    {
    }

    public function deleted(Budget $budget): void
    {
    }

    public function restoring(Budget $budget): void
    {
    }

    public function restored(Budget $budget): void
    {
    }

    public function forceDeleting(Budget $budget): void
    {
    }

    public function forceDeleted(Budget $budget): void
    {
    }
}
