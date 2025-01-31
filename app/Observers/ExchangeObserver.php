<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Exchange;
use App\Models\Expense;
use App\Models\Income;

class ExchangeObserver
{
    public function creating(Exchange $exchange): void
    {
        $lastId = Exchange::withTrashed()->latest('id')->value('id') ?? 0;
        $exchange->slug = $lastId + 1;
        $exchange->date = $exchange->date ?? $exchange->created_at;
    }

    public function created(Exchange $exchange): void
    {
        $expense = Expense::create([
                'title' => 'перевод',
                'amount' => $exchange->amount_from,
                'account_id' => $exchange->account_from,
                'currency' => $exchange->currency_from,
                'date' => $exchange->date,
                'user_id' => $exchange->user_id,
            ]
        );
        $expense->user()->associate($exchange->user_id);
        $expense->account()->associate($exchange->account_from);
        $category = Category::firstOrCreate(['title' => 'Переводы']);
        $expense->categories()->sync($category);
        $expense->save();
        $income = new Income();
        $income->title = 'перевод';
        $income->amount = $exchange->amount_to;
        $income->currency = $exchange->currency_to;
        $income->date = $exchange->date;
        $income->account_id = $exchange->account_to;
        $income->user_id = $exchange->user_id;
        $income->source = 'перевод';
        $income->save();
        $income->user()->associate($exchange->user_id);
        $income->account()->associate($exchange->account_to);
        $income->save();

        $exchange->update(['income_id' => $income->id, 'expense_id' => $expense->id]);
        $exchange->save();
    }

    public function updating(Exchange $exchange): void
    {
        $income = $exchange->income;
        $expense = $exchange->expense;
        $income->update([
            'amount' => $exchange->amount_to,
            'date' => $exchange->date,
            'currency' => $exchange->currency_to,
        ]);
        $exchange->income->account()->associate($exchange->account_to);
        $exchange->income->user()->associate($exchange->user_id);
        $exchange->income->save();
        $expense->update([
            'amount' => $exchange->amount_from,
            'date' => $exchange->date,
            'currency' => $exchange->currency_from,
        ]);
        $exchange->expense->account()->associate($exchange->account_from);
        $exchange->expense->user()->associate($exchange->user_id);
        $exchange->expense->save();
    }

    public function updated(Exchange $exchange): void
    {
    }

    public function saving(Exchange $exchange): void
    {
    }

    public function saved(Exchange $exchange): void
    {
    }

    public function deleting(Exchange $exchange): void
    {
    }

    public function deleted(Exchange $exchange): void
    {
        $exchange->income->delete();
        $exchange->expense->delete();
    }

    public function restoring(Exchange $exchange): void
    {
    }

    public function restored(Exchange $exchange): void
    {
    }

    public function forceDeleting(Exchange $exchange): void
    {
    }

    public function forceDeleted(Exchange $exchange): void
    {
    }
}
