<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'account_id',
        'normalized_title',
        'source',
        'amount',
        'currency',
        'created_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($income) {
            $income->normalized_title = mb_strtolower($income->title);
            $lastIncomeId = Income::withTrashed()->latest('id')->value('id') ?? 0;
            $income->slug = $lastIncomeId + 1;
        });
        static::created(function ($income) {
            $account = Account::find($income->account_id);
            $amount = $income->amount;
            $account->update(['amount' => $account->amount + $amount]);
        });
        static::updating(function ($income) {
            $income->normalized_title = mb_strtolower($income->title);
            $account = Account::find($income->account_id);
            $amount = $income->getOriginal('amount');
            $account->update(['amount' => $account->amount - $amount]);
        });
        static::updated(function ($income) {
            $account = Account::find($income->account_id);
            $amount = $income->amount;
            $account->update(['amount' => $account->amount + $amount]);
        });
        static::deleting(function ($income) {
            $account = Account::find($income->account_id);
            $amount = $income->amount;
            $account->update(['amount' => $account->amount - $amount]);
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
                'type' => 'text',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
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

    public function getTypeAttribute(): string
    {
        return 'income';
    }

    public function exchanges(): HasMany
    {
        return $this->hasMany(Exchange::class);
    }
}
