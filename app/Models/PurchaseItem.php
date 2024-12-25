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
        'purchase_id',
        'title',
        'price',
        'quantity',
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public static function getFields()
    {

        return [
            'id' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => false,
                'editable' => false,
            ],
            'purchase_id' => [
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
}
