<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Expense
{
    use HasFactory, SoftDeletes;

    // Add a dynamic property to every instance of Purchase
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($purchase) {
            $purchase->normalized_title = mb_strtolower($purchase->title);
            $purchase->amount_calculated = 0;
            $lastIncomeId = Purchase::withTrashed()->latest('id')->value('id') ?? 0;
            $purchase->slug = $lastIncomeId + 1;
        });
        static::created(function ($purchase) {
            $account = Account::find($purchase->account_id);
            $amount = $purchase->amount;
            $account->update(['amount' => $account->amount - $amount]);
        });
        static::updating(function ($purchase) {
            $purchase->normalized_title = mb_strtolower($purchase->title);
            $account = Account::find($purchase->account_id);
            $amount = $purchase->getOriginal('amount');
            $account->update(['amount' => $account->amount + $amount]);
        });
        static::updated(function ($purchase) {
            $account = Account::find($purchase->account_id);
            $amount = $purchase->amount;
            $account->update(['amount' => $account->amount - $amount]);
        });
        static::deleting(function ($purchase) {
            $account = Account::find($purchase->account_id);
            $amount = $purchase->amount;
            $account->update(['amount' => $account->amount + $amount]);
        });
    }

    public static function getFields(): array
    {
        $budget = Budget::where('slug', 'LIKE', auth()->user()->settings->active_budget)->first();
        $accounts = $budget->accounts;
        $users = $budget->users;
        return [
            'title' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'amount' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'currency' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => false,
                'values' => config('currencies')
            ],
            'user' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => $users,
                'multiple' => false,
                'showField' => 'name',
            ],
            'source' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => Category::all(),
                'multiple' => true,
                'showField' => 'title',
            ],
            'account' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => $accounts,
                'multiple' => false,
                'showField' => 'title',
            ],
            'created_at' => [
                'type' => 'date',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true
            ]
        ];
    }

    public function getTypeAttribute(): string
    {
        return 'purchase';
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
