<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetHistory extends Model
{
    use SoftDeletes;

    protected $table = 'budget_history';

    protected $fillable = [
        'budget_id',
        'amount',
        'operation_type',
        'description',
        'balance_after',
        'performed_at',
    ];

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
}
