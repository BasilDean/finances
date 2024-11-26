<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
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

        static::creating(function ($expense) {
            $expense->normalized_title = mb_strtolower($expense->title);
        });

        static::updating(function ($expense) {
            $expense->normalized_title = mb_strtolower($expense->title);
        });
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
