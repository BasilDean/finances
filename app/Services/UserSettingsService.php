<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserSettingsService
{
    /**
     * Retrieve the user's active budget.
     *
     * @return Budget|null
     * @throws ModelNotFoundException
     */
    public function getActiveBudget(): ?Budget
    {
        $user = Auth::user();

        if (!isset($user->settings['active_budget'])) {
            return null;
        }

        // Retrieve the active budget based on the user's settings
        /** @noinspection NullPointerExceptionInspection */
        return Budget::where('slug', $user->settings['active_budget'])->firstOrFail();
    }

    public function setActiveBudget(User $user, string $slug): void
    {
        $user->settings()->update(['active_budget' => $slug]);
    }

    /**
     * Retrieve any user-specific setting value.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getUserSetting(string $key, mixed $default = null): mixed
    {
        $user = Auth::user();
        return $user->settings[$key] ?? $default;
    }
}
