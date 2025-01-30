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
        'date' => 'datetime:Y-m-d H:i',
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
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
