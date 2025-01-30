<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'normalized_title',
        'slug',
        'regular',
        'frequency',
        'amount',
        'currency',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug'; // Or the column you use for route binding
    }

    protected function casts(): array
    {
        return [
            'regular' => 'boolean',
        ];
    }
}
