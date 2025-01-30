<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_id',
        'title',
        'price',
        'quantity',
    ];

    // Add a dynamic property to every instance of Purchase

    public static function getFields(): array
    {

        return [
            'id' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'expense_id' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'title' => [
                'type' => 'string',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'quantity' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
            'price' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
            ],
        ];
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }
}
