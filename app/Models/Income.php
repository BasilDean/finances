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
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($income) {
            $income->normalized_title = mb_strtolower($income->title);
        });

        static::updating(function ($income) {
            $income->normalized_title = mb_strtolower($income->title);
        });
    }

    public static function getFields()
    {
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
                'editable' => true,
                'values' => config('currencies')
            ],
            'user' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => [
                    'cash',
                    'account'
                ]
            ],
            'category' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => [
                    'cash',
                    'account'
                ]
            ],
            'account' => [
                'type' => 'relation',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => [
                    'cash',
                    'account'
                ]
            ]
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
