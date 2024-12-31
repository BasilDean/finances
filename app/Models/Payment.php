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

    public static function getFields(): array
    {
        return [
            'title' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
//                'filter' => true,
//                'sortable' => true,
//                'searchable' => true,
//                'required' => true,
//                'unique' => true,
            ],
            'amount' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'regular' => [
                'type' => 'boolean',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'date' => [
                'type' => 'date',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'frequency' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => true,
                'values' => ['once', 'daily', 'weekly', 'monthly', 'yearly']
            ],
            'currency' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => true,
                'values' => config('currencies')
            ],
            'total' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => true,
            ],
            'total_paid' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'total_due' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'credit_percent' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => true,
            ],
            'deadline' => [
                'type' => 'date',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => true,
            ]
        ];
    }

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
