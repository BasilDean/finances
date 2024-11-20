<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Budget extends Model
{
    use SoftDeletes, HasFactory;

// Adding the slug field to the fillable property
    protected $fillable = [
        'title',
        'main_currency',
        'slug'
    ];

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

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(related: User::class);
    }

    public function accounts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(related: Account::class);
    }

    public function getBudgetTotal(): String
    {
        $accountsArray = $this->accounts->toArray();
        $total = array_reduce($accountsArray, function ($sum, $item) {
            $currencyRate = new CurrencyRate();
//            dd($item['currency'], $this->main_currency);
            if ($item['currency'] === $this->main_currency) {
                $sum += $item['amount'];
            } else {
                $amountIRubbles= $currencyRate->convertToRubbles($item['currency'], $item['amount']);
//                dd($amountIRubbles, $item);
                if ($this->main_currency === 'RUB') {
                    $sum += $amountIRubbles;
                } else {
                    $sum += $currencyRate->convertToCurrency($this->main_currency, $amountIRubbles);
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
}
