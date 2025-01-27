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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($budget) {
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
        static::updating(static function ($budget) {
            $total = $budget->getBudgetTotal();
            if ($budget->balance !== $total) {
                $budget->updateBudgetTotal($total);
                $diff = $budget->balance - $total;
                BudgetHistory::create([
                    'budget_id' => $budget->id,
                    'amount' => $diff,
                    'operation_type' => $diff > 0 ? 'expense' : 'income',
                    'description' => 'Budget balance changed',
                    'balance_after' => $total,
                    'performed_at' => now(),
                ]);
            }
        });
    }

    public function getBudgetTotal(): string
    {
        $accountsArray = $this->accounts->toArray();
        $total = array_reduce($accountsArray, callback: function ($sum, $item) {
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
        }, initial: 0);
        return $total;
    }

    public function updateBudgetTotal($total): void
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

    public function history(): HasMany
    {
        return $this->hasMany(BudgetHistory::class);
    }
}
