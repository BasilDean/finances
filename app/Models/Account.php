<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    ];

    public static function getFields(): array
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
                'values' => config('currencies'),
                'filter' => true,
                'filter-type' => 'select'
            ],
            'type' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => [
                    'cash',
                    'account'
                ],
                'filter' => true,
                'filter-type' => 'select'
            ]
        ];
    }


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
            $account->normalized_title = mb_strtolower($account->title);
        });

        static::updating(function ($account) {
            $account->normalized_title = mb_strtolower($account->title);
        });
        static::created(function ($account) {
            if (auth()->user() && auth()->user()->settings['active_budget']) {
                $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
                if ($budget) {
                    $account->budgets()->attach($budget);
                    $total = $budget->getBudgetTotal();
                    if ($budget->balance !== $total) {
                        $budget->updateBudgetTotal($total);
                    }
                }
            }
        });
        static::updated(function ($account) {
            if (auth()->user() && auth()->user()->settings) {
                $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
                $total = $budget->getBudgetTotal();
                if ($budget->balance !== $total) {
                    $budget->updateBudgetTotal($total);
                }
            }
        });
        static::deleted(function ($account) {
            $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->first();
            $total = $budget->getBudgetTotal();
            if ($budget->balance !== $total) {
                $budget->updateBudgetTotal($total);
            }
        });
    }

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
}
