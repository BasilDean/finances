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
        $account->amount = 0;
        $account->normalized_title = mb_strtolower($account->title);
    }

    private function generateUniqueSlug(string $title): string
    {
        $originalSlug = Str::slug($title);
        $latestSlug = Account::withTrashed()
            ->where('slug', 'LIKE', "{$originalSlug}%")
            ->latest('id') // Get the most recent entry
            ->value('slug'); // Fetch the slug

        if ($latestSlug) {
            $number = (int)str_replace("{$originalSlug}-", '', $latestSlug) + 1;
            return "{$originalSlug}-{$number}";
        }

        return $originalSlug;
    }

    public function updating(Account $account): void
    {
        $account->normalized_title = mb_strtolower($account->title);
    }

    public function updated(Account $account): void
    {
        $this->synchronizeBudgetTotal();
    }

    // Helper methods

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

    public function deleted(Account $account): void
    {
        $this->synchronizeBudgetTotal();
    }

    private function associateBudgetWithAccountAndSynchronize($budget, Account $account): void
    {
        $account->budgets()->attach($budget);
        $this->synchronizeBudgetTotal();
    }
}
