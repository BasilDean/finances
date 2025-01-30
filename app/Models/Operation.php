<?php

namespace App\Models;

use App\OperationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'performed_at', // Add your field here
    ];

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
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'performed_at' => 'datetime:Y-m-d H:i',
            'operation_type' => OperationType::class,
        ];
    }
}
