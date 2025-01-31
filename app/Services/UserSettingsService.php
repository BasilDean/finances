<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserSettingsService
{
    /**
     * Retrieve the user's active budget.
     *
     * @return Budget
     * @throws ModelNotFoundException
     */
    public function getActiveBudget(): Budget
    {
        $user = Auth::user();

        if (!isset($user->settings['active_budget'])) {
            throw new Exception('No active budget set for the user.');
        }

        // Retrieve the active budget based on the user's settings
        return Budget::where('slug', $user->settings['active_budget'])->firstOrFail();
    }

    public function setActiveBudget(User $user, string $slug)
    {
        $user->settings['active_budget'] = $slug;
        $user->settings->save();
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
