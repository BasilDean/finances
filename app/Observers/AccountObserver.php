<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\Budget;
use Illuminate\Support\Str;

class AccountObserver
{
    public function creating(Account $account): void
    {

        $slug = Str::slug($account->title);
        $originalSlug = $slug;
        $counter = 1;
        // Ensure the slug is unique by including soft deleted items
        while (Account::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $account->slug = $slug;
        $account->normalized_title = mb_strtolower($account->title);
    }

    public function created(Account $account): void
    {
        if (auth()->user() && auth()->user()->settings['active_budget']) {
            $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
            if ($budget) {
                $account->budgets()->attach($budget);
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
        $account->normalized_title = mb_strtolower($account->title);
    }

    public function updated(Account $account): void
    {
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
    }

    public function deleted(Account $account): void
    {
        $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
        $total = $budget->getBudgetTotal();
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($budget->balance != $total) {
            $budget->updateBudgetTotal($total);
        }
    }

    public function restoring(Account $account): void
    {
    }

    public function restored(Account $account): void
    {
    }

    public function forceDeleting(Account $account): void
    {
    }

    public function forceDeleted(Account $account): void
    {
    }
}
