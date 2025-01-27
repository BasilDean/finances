<?php

namespace App\Observers;

use App\Models\Account;
use App\Services\BudgetService;
use App\Services\UserSettingsService;
use Illuminate\Support\Str;

class AccountObserver
{
    private BudgetService $budgetService;
    private UserSettingsService $userSettingsService;

    public function __construct(BudgetService $budgetService, UserSettingsService $userSettingsService)
    {
        $this->budgetService = $budgetService;
        $this->userSettingsService = $userSettingsService;
    }

    public function creating(Account $account): void
    {
        $account->slug = $this->generateUniqueSlug($account->title);
        $account->normalized_title = mb_strtolower($account->title);
    }

    private function generateUniqueSlug(string $title): string
    {
        $originalSlug = Str::slug($title);
        $uniqueSlug = $originalSlug;
        $counter = 1;

        while (Account::withTrashed()->where('slug', 'LIKE', "{$originalSlug}%")->exists()) {
            $uniqueSlug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $uniqueSlug;
    }

    public function created(Account $account): void
    {
        $budget = $this->userSettingsService->getActiveBudget();
        if ($budget) {
            $this->associateBudgetWithAccountAndSynchronize($budget, $account);
        }
    }

    private function associateBudgetWithAccountAndSynchronize($budget, Account $account): void
    {
        $account->budgets()->attach($budget);
        $this->synchronizeBudgetTotal();
    }

    private function synchronizeBudgetTotal(): void
    {
        $budget = $this->userSettingsService->getActiveBudget();
        if ($budget) {
            $total = $this->budgetService->calculateBudgetTotal($budget);
            if ((int)$budget->balance !== (int)$total) {
                $this->budgetService->updateBudgetTotal($budget, $total);
            }
        }
    }

    // Helper methods

    public function updating(Account $account): void
    {
        $account->normalized_title = mb_strtolower($account->title);
    }

    public function updated(Account $account): void
    {
        $this->synchronizeBudgetTotal();
    }

    public function deleted(Account $account): void
    {
        $this->synchronizeBudgetTotal();
    }
}
