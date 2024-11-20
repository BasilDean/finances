<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Account extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'currency',
        'type',
        'slug',
    ];


// Adding an observer method to handle creating event
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($account) {
            $slug = Str::slug($account->title);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure the slug is unique by including soft deleted items
            while (self::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $account->slug = $slug;
        });
        static::created(function ($account) {
            $budget = Budget::where('slug', session()->get('default_budget'))->firstOrFail();
            $account->budgets()->attach($budget);
            $total = $budget->getBudgetTotal();
            if ($budget->balance !== $total) {
                $budget->updateBudgetTotal($total);
            }
        });
        static::updated(function ($account) {
            $budget = Budget::where('slug', session()->get('default_budget'))->firstOrFail();
            $total = $budget->getBudgetTotal();
            if ($budget->balance !== $total) {
                $budget->updateBudgetTotal($total);
            }
        });
        static::deleted(function ($account) {
            $budget = Budget::where('slug', session()->get('default_budget'))->firstOrFail();
            $total = $budget->getBudgetTotal();
            if ($budget->balance !== $total) {
                $budget->updateBudgetTotal($total);
            }
        });
    }

    public function budgets(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Budget::class);
    }

    public function incomes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(related: Income::class);
    }

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(related: Expense::class);
    }
}
