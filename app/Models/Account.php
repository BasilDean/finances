<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property float $balance
 */
class Account extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'currency',
        'type',
    ];

    public function budgets(): BelongsToMany
    {
        return $this->belongsToMany(Budget::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(related: Income::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(related: Expense::class);
    }

    public function exchangesFrom(): HasMany
    {
        return $this->hasMany(related: Exchange::class, foreignKey: 'account_from');
    }

    public function exchangesTo(): HasMany
    {
        return $this->hasMany(related: Exchange::class, foreignKey: 'account_to');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }
}
