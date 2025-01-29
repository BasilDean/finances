<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    use HasFactory, SoftDeletes;

    // Specify the fields that should be cast to dates
    protected $dates = [
        'created_at',
        'updated_at',
        'date', // Add your field here
    ];
    protected $casts = [
        'date' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'amount_to',
        'account_from',
        'account_to',
        'currency_from',
        'currency_to',
        'exchange_rate',
        'amount_from',
        'user_id',
        'income_id',
        'expense_id',
        'created_at',
        'date',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleted(static function ($exchange) {
        });
    }

    public static function getFields(): array
    {
        $budget = Budget::where('slug', 'LIKE', auth()->user()->settings->active_budget)->first();
        $accounts = $budget->accounts;
        $users = $budget->users;
        return [
            'amount_from' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'currency_from' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => false,
            ],
            'account_from' => [
                'type' => 'relation',
                'hideOnMobile' => true,
                'show' => false,
                'editable' => true,
                'required' => true,
                'values' => $accounts,
                'multiple' => false,
                'showField' => 'title',
            ],
            'amount_to' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'required' => true,
            ],
            'account_to' => [
                'type' => 'relation',
                'hideOnMobile' => true,
                'show' => false,
                'editable' => true,
                'required' => true,
                'values' => $accounts,
                'multiple' => false,
                'showField' => 'title',
            ],
            'currency_to' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => false,
            ],
            'exchange_rate' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => false,
            ],
            'oficial_rate' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'user' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => true,
                'values' => $users,
                'multiple' => false,
                'showField' => 'name',
            ],
            'date' => [
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

    public function accountFrom(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_from');
    }

    public function accountTo(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_to');
    }

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class);
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
