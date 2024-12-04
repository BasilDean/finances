<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    protected function casts(): array
    {
        return [
            'regular' => 'boolean',
        ];
    }
}
