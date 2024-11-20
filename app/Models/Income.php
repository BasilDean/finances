<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
