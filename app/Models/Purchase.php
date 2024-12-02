<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Expense
{
    use HasFactory, SoftDeletes;

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
