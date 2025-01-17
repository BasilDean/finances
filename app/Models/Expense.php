<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes, HasFactory;

    // Add a dynamic property to every instance of Expense
    protected $fillable = [
        'title',
        'amount',
        'has_items',
        'currency',
        'date'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($expense) {

            $expense->title = ucfirst($expense->title);
            $expense->normalized_title = mb_strtolower($expense->title);
            $lastIncomeId = Expense::withTrashed()->latest('id')->value('id') ?? 0;
            $expense->slug = $lastIncomeId + 1;
            if (empty($expense->date)) {
                $expense->date = $expense->created_at;
            }
        });
        static::created(static function ($expense) {
            $account_id = $expense->account_id;
            $account = Account::find($account_id);
            $amount = $expense->amount;
            $account->update(['amount' => $account->amount - $amount]);

            Operation::create([
                'account_id' => $account_id,
                'amount' => $amount,
                'operation_type' => 'expense',
                'operation_id' => $expense->id,
                'description' => $expense->title,
                'balance_after' => $account->amount,
                'performed_at' => $expense->date,
            ]);
        });
        static::updating(static function ($expense) {
            $expense->normalized_title = mb_strtolower($expense->title);
            $account = Account::find($expense->account_id);
            $amount = $expense->getOriginal('amount');
            $account->update(['amount' => $account->amount + $amount]);
        });
        static::updated(static function ($expense) {
            $account_id = $expense->account_id;

            $account = Account::find($account_id);
            $amount = $expense->amount;
            $account->update(['amount' => $account->amount - $amount]);

            $operation = Operation::where('operation_id', $expense->id)
                ->where('operation_type', 'expense')
                ->first();
            $operation->update([
                'account_id' => $account_id,
                'amount' => $amount,
                'balance_after' => $account->amount,
                'description' => $expense->title,
                'performed_at' => $expense->date,
            ]);
        });
        static::deleting(static function ($expense) {
            $account = Account::find($expense->account_id);
            $amount = $expense->amount;
            $account->update(['amount' => $account->amount + $amount]);

            $operation = Operation::where('operation_id', $expense->id)
                ->where('operation_type', 'expense')
                ->first();

            $operation->delete();
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
                'show' => false,
                'editable' => false,
                'values' => config('currencies')
            ],
            'has_items' => [
                'type' => 'boolean',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
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

    public function getTypeAttribute(): string
    {
        return 'expense';
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function exchanges(): HasMany
    {
        return $this->hasMany(Exchange::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
