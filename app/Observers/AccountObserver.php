<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Budget;
use Illuminate\Support\Str;

class AccountObserver
{
    public function creating(Account $account): void
    {
        // Generate a unique slug for the account
        $originalSlug = Str::slug($account->title);
        $slug = $originalSlug;

        $counter = 1;
        while (Account::withTrashed()->where('slug', 'LIKE', "{$originalSlug}%")->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        $account->slug = $slug;
        $account->normalized_title = mb_strtolower($account->title);
    }

    public function created(Account $account): void
    {
        // TODO: Move user settings and active budget logic to a dedicated service
        if (auth()->user() && auth()->user()->settings['active_budget']) {
            $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
            if ($budget) {
                // TODO: Move budget association logic to a Budget service
                $account->budgets()->attach($budget);

                // TODO: Move budget total calculation and update logic to a Budget service
                $total = $budget->getBudgetTotal();
                /** @noinspection TypeUnsafeComparisonInspection */
                if ($budget->balance != $total) {
                    $budget->updateBudgetTotal($total);
                }
            }
        }
    }

    public function updating(Account $account): void
    {
        // Normalize the title before saving
        $account->normalized_title = mb_strtolower($account->title);
    }

    public function updated(Account $account): void
    {
        // TODO: Move budget total recalculation logic to a Budget service
        if (auth()->user() && auth()->user()->settings) {
            $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
            $total = $budget->getBudgetTotal();
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($budget->balance != $total) {
                $budget->updateBudgetTotal($total);
            }
        }
    }

    public function deleting(Account $account): void
    {
        // Add logic if required to handle soft deletion
    }

    public function deleted(Account $account): void
    {
        // TODO: Move budget total recalculation logic to a Budget service
        $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
        $total = $budget->getBudgetTotal();
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($budget->balance != $total) {
            $budget->updateBudgetTotal($total);
        }
    }

    public function restoring(Account $account): void
    {
        // Add logic if something needs to happen when restoring a soft-deleted account
    }

    public function restored(Account $account): void
    {
        // Add logic if something needs to happen after an account is restored
    }

    public function forceDeleting(Account $account): void
    {
        // Add logic if something needs to happen during permanent account deletion
    }

    public function forceDeleted(Account $account): void
    {
        // Add logic if something needs to happen after an account is permanently deleted
    }
}
