<?php

namespace App\Traits;

use App\Models\Budget;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasBudgets
{
    /**
     * Define a many-to-many relationship with Budget.
     *
     * @return BelongsToMany
     */
    public function budgets(): BelongsToMany
    {
        return $this->belongsToMany(Budget::class, 'budget_user');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(Setting::class);
    }

    /**
     * Get the default currency from the account settings.
     *
     * @return string|null
     */
    public function getDefaultCurrencyAttribute(): ?string
    {
        $setting = $this->settings['default_currency'];
        return $setting ?? null;
    }
}
