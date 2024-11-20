<?php

namespace App\Traits;

use App\Models\Budget;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function settings(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Setting::class, 'setting_user');
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
