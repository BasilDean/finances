<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'currency',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($expense) {
            $expense->normalized_title = mb_strtolower($expense->title);
            $lastIncomeId = Expense::withTrashed()->latest('id')->value('id') ?? 0;
            $expense->slug = $lastIncomeId + 1;
        });
        static::created(function ($expense) {
            $account = Account::find($expense->account_id);
            $amount = $expense->amount;
            $account->update(['amount' => $account->amount - $amount]);
        });
        static::updating(function ($expense) {
            $expense->normalized_title = mb_strtolower($expense->title);
            $account = Account::find($expense->account_id);
            $amount = $expense->getOriginal('amount');
            $account->update(['amount' => $account->amount + $amount]);
        });
        static::updated(function ($expense) {
            $account = Account::find($expense->account_id);
            $amount = $expense->amount;
            $account->update(['amount' => $account->amount - $amount]);
        });
        static::deleting(function ($expense) {
            $account = Account::find($expense->account_id);
            $amount = $expense->amount;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
