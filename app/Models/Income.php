<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'date',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(static function ($income) {
            $income->normalized_title = mb_strtolower($income->title);
            $lastIncomeId = Income::withTrashed()->latest('id')->value('id') ?? 0;
            $income->slug = $lastIncomeId + 1;
            if (empty($income->date)) {
                $income->date = $income->created_at;
            }
        });
        static::created(static function ($income) {
            $account_id = $income->account_id;
            $account = Account::find($account_id);
            $amount = $income->amount;
            $account->update(['amount' => $account->amount + $amount]);
            Operation::create([
                'account_id' => $account_id,
                'amount' => $amount,
                'operation_type' => 'income',
                'operation_id' => $income->id,
                'description' => $income->title,
                'balance_after' => $account->amount,
                'performed_at' => $income->date,
            ]);
        });
        static::updating(static function ($income) {
            $income->normalized_title = mb_strtolower($income->title);
            $account = Account::find($income->account_id);
            $amount = $income->getOriginal('amount');
            $account->update(['amount' => $account->amount - $amount]);
        });
        static::updated(static function ($income) {
            $account_id = $income->account_id;
            $account = Account::find($account_id);
            $amount = $income->amount;
            $account->update(['amount' => $account->amount + $amount]);
            $operation = Operation::where('operation_id', $income->id)
                ->where('operation_type', 'income')
                ->first();
            $operation->update([
                'account_id' => $account_id,
                'amount' => $amount,
                'balance_after' => $account->amount,
                'description' => $income->title,
                'performed_at' => $income->date,
            ]);
        });
        static::deleting(static function ($income) {
            $account = Account::find($income->account_id);
            $amount = $income->amount;
            $account->update(['amount' => $account->amount - $amount]);


            $operation = Operation::where('operation_id', $income->id)
                ->where('operation_type', 'income')
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

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
