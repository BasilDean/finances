<?php

namespace App\Models;

use App\OperationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_id',
        'amount',
        'operation_type',
        'operation_id',
        'description',
        'balance_after',
        'performed_at',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    protected function casts(): array
    {
        return [
            'performed_at' => 'timestamp',
            'operation_type' => OperationType::class,
        ];
    }
}
