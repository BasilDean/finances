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
    // Specify the fields that should be cast to dates
    protected $dates = [
        'created_at',
        'updated_at',
        'date', // Add your field here
    ];
    protected $casts = [
        'date' => 'datetime:Y-m-d H:i:s',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
