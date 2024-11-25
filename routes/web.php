<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {

    if (!auth()->check()) {
        return redirect()->route('login');
    }
    if (session()->has('default_budget')) {
        return redirect()->route('budgets.show', ['budget' => session('default_budget')]);
    }
    return redirect()->route('budgets.index');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
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
});
Route::get('autocomplete', [IncomeController::class, 'autocomplete'])->name('income.autocomplete');

require __DIR__.'/auth.php';
