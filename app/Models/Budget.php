<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Budget extends Model
{
    use SoftDeletes, HasFactory;

// Adding the slug field to the fillable property
    protected $fillable = [
        'title',
        'currency',
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
            'balance' => [
                'type' => 'number',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => false,
            ],
            'currency' => [
                'type' => 'list',
                'hideOnMobile' => false,
                'show' => true,
                'editable' => true,
                'values' => config('currencies')
            ]
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            $slug = Str::slug($budget->title);
            $originalSlug = $slug;
            $counter = 1;

            // Ensure the slug is unique by including soft deleted items
            while (self::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $budget->slug = $slug;
        });
        static::updating(function ($budget) {
            $total = $budget->getBudgetTotal();
            if ($budget->balance !== $total) {
                $budget->updateBudgetTotal($total);
            }
        });
    }

    public function getBudgetTotal(): string
    {
        $accountsArray = $this->accounts->toArray();
        $total = array_reduce($accountsArray, function ($sum, $item) {
            $currencyRate = new CurrencyRate();
//            dd($item['currency'], $this->currency);
            if ($item['currency'] === $this->currency) {
                $sum += $item['amount'];
            } else {
                $amountIRubbles = $currencyRate->convertToRubbles($item['currency'], $item['amount']);
//                dd($amountIRubbles, $item);
                if ($this->currency === 'RUB') {
                    $sum += $amountIRubbles;
                } else {
                    $sum += $currencyRate->convertToCurrency($this->currency, $amountIRubbles);
                }
            }
            return $sum;
        }, 0);
        return $total;
    }

    public function updateBudgetTotal($total)
    {
        $this->balance = $total;
        $this->save();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(related: User::class);
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(related: Account::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(related: Payment::class);
    }
}
