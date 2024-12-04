<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (auth()->user() && auth()->user()->settings && auth()->user()->settings['active_budget']) {
        return redirect()->route('budgets.show', ['budget' => auth()->user()->settings['active_budget']]);
    }
    return redirect()->route('budgets.index');
})->name('home');

Route::get('/dashboard', function () {
    if (auth()->user() && auth()->user() && auth()->user()->settings && auth()->user()->settings['active_budget']) {
        return redirect()->route('budgets.show', ['budget' => auth()->user()->settings['active_budget']]);
    }
    return redirect()->route('budgets.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->prefix('budgets')->group(function () {
    Route::get('/', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/{budget:slug}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::get('/{budget:slug}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::patch('/{budget:slug}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/{budget:slug}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
});

Route::middleware('auth')->prefix('accounts')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::post('/', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/{account:slug}', [AccountController::class, 'show'])->name('accounts.show');
    Route::get('/{account:slug}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::patch('/{account:slug}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/{account:slug}', [AccountController::class, 'destroy'])->name('accounts.destroy');
});

Route::middleware('auth')->prefix('income')->group(function () {
    Route::get('/', [IncomeController::class, 'index'])->name('income.index');
    Route::get('/create', [IncomeController::class, 'create'])->name('income.create');
    Route::post('/', [IncomeController::class, 'store'])->name('income.store');
    Route::get('/{income:id}', [IncomeController::class, 'show'])->name('income.show');
    Route::get('/{income:slug}/edit', [IncomeController::class, 'edit'])->name('income.edit');
    Route::patch('/{income:slug}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/{income:slug}', [IncomeController::class, 'destroy'])->name('income.destroy');
    Route::get('autocomplete/title', [IncomeController::class, 'autocomplete'])->name('income.autocomplete.title');
});

Route::middleware('auth')->prefix('expense')->group(function () {
    Route::get('/', [ExpenseController::class, 'index'])->name('expense.index');
    Route::get('/create', [ExpenseController::class, 'create'])->name('expense.create');
    Route::post('/', [ExpenseController::class, 'store'])->name('expense.store');
    Route::get('/{expense:id}', [ExpenseController::class, 'show'])->name('expense.show');
    Route::get('/{expense:slug}/edit', [ExpenseController::class, 'edit'])->name('expense.edit');
    Route::patch('/{expense:slug}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/{expense:slug}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
    Route::get('autocomplete/title', [ExpenseController::class, 'autocompleteTitle'])->name('expense.autocomplete.title');
    Route::get('autocomplete/category', [ExpenseController::class, 'autocompleteCategory'])->name('expense.autocomplete.category');
});

Route::middleware('auth')->prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/{payments:id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/{payments:slug}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::patch('/{payments:slug}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/{payments:slug}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('autocomplete/title', [PaymentController::class, 'autocompleteTitle'])->name('payments.autocomplete.title');
    Route::get('autocomplete/category', [PaymentController::class, 'autocompleteCategory'])->name('payments.autocomplete.category');
});

require __DIR__ . '/auth.php';
