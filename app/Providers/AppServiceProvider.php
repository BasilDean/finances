<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Observers\AccountObserver;
use App\Observers\BudgetObserver;
use App\Observers\CategoryObserver;
use App\Observers\ExpenseObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Vite::prefetch(concurrency: 3);
        Account::observe(AccountObserver::class);
        Budget::observe(BudgetObserver::class);
        Expense::observe(ExpenseObserver::class);
        Category::observe(CategoryObserver::class);
    }
}
