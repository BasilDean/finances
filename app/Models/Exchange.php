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

        static::creating(static function ($exchange) {
            $lastId = Exchange::withTrashed()->latest('id')->value('id') ?? 0;
            $exchange->slug = $lastId + 1;
        });
        static::created(static function ($exchange) {
            $expense = new Expense();
            $expense->title = 'перевод';
            $expense->amount = $exchange->amount_from;
            $expense->currency = $exchange->currency_from;
            $expense->date = $exchange->date;
            $expense->user()->associate($exchange->user_id);
            $expense->account()->associate($exchange->account_from);
            $expense->save();
            $expense->categories()->sync(Category::firstOrCreate(['title' => 'Переводы', 'parent_id' => 0])->id);
            $expense->save();

            $income = new Income();
            $income->title = 'перевод';
            $income->amount = $exchange->amount_to;
            $income->currency = $exchange->currency_to;
            $income->date = $exchange->date;
            $income->user()->associate($exchange->user_id);
            $income->account()->associate($exchange->account_to);
            $income->source = 'перевод';
            $income->save();

            $exchange->update(['income_id' => $income->id, 'expense_id' => $expense->id]);
            $exchange->save();
        });
        static::updating(static function ($exchange) {
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($exchange->getOriginal('amount_from') && $exchange->getOriginal('amount_from') != $exchange->amount_from) {
                $exchange->expense->amount = $exchange->amount_from;
            }
            /** @noinspection TypeUnsafeComparisonInspection */
            if ($exchange->getOriginal('amount_to') && $exchange->getOriginal('amount_to') != $exchange->amount_to) {
                $exchange->income->amount = $exchange->amount_to;
            }
            $exchange->income->date = $exchange->date;
            $exchange->income->currency = $exchange->currency_to;
            $exchange->income->account()->associate($exchange->account_to);
            $exchange->income->user()->associate($exchange->user_id);
            $exchange->income->save();
            $exchange->expense->date = $exchange->date;
            $exchange->expense->currency = $exchange->currency_from;
            $exchange->expense->account()->associate($exchange->account_from);
            $exchange->expense->user()->associate($exchange->user_id);
            $exchange->expense->save();
        });
        static::deleted(static function ($exchange) {
            $income = Income::find($exchange->income_id);
            $expense = Expense::find($exchange->expense_id);
            $income->delete();
            $expense->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
