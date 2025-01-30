<?php

namespace App\Http\Resources;

use App\Models\Payment;
use App\Services\UserSettingsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Payment */
class PaymentResource extends JsonResource
{

    /**
     * Define the fields shown to the frontend.
     *
     * @param string|null $context The context for the fields (e.g., 'edit', 'show').
     * @return array The field definitions for the given context.
     */
    public static function getFields(?string $context = null): array
    {
        $users = self::getUsers();
        $accounts = self::getAccounts();
        // Default fields (shared across contexts)
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text']),
            'amount' => self::makeField('number', 'amount', false, ['filter' => true, 'filter-type' => 'text']),
            'regular' => self::makeField('boolean', 'regular'),
            'date' => self::makeField('date', 'date'),
            'user' => self::makeField('relation', 'user', false,
                ['values' => $users, 'multiple' => false, 'showField' => 'name']),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [
                'frequency' => self::makeField('list', 'frequency', false, ['values' => ['once', 'daily', 'weekly', 'monthly', 'yearly']]),
                'currency' => self::makeField('list', 'currency', false, ['values' => self::getCurrencies(), 'filter' => true, 'filter-type' => 'select']),
                'total' => self::makeField('number', 'total'),
                'credit_percent' => self::makeField('number', 'credit_percent'),
                'deadline' => self::makeField('date', 'deadline'),
            ],
            'show' => [
            ],
            'admin' => [
                'total_paid' => self::makeField('number', 'total_paid'),
                'total_due' => self::makeField('number', 'total_due'),
            ],
            default => [],
        };

        // Return merged fields sorted by the 'order' field
        return collect(array_merge($defaultFields, $contextFields))
            ->sortBy('order')
            ->toArray();
    }

    private static function getUsers(): Collection
    {
        return (new UserSettingsService())->getActiveBudget()->users;
    }

    private static function getAccounts(): Collection
    {
        return (new UserSettingsService())->getActiveBudget()->accounts;
    }

    private static function makeField(
        string $type,
        string $name,
        bool   $hideOnMobile = false,
        array  $additionalAttributes = []
    ): array
    {
        $orderMap = self::fieldOrderMap();
        if (!isset($orderMap[$name])) {
            Log::warning("Field '{$name}' does not have an explicit position in the field order map.");
        }
        return array_merge([
            'type' => $type,
            'hideOnMobile' => $hideOnMobile,
            'order' => $orderMap[$name] ?? 100, // Default to 100 for unordered fields
        ], $additionalAttributes);
    }

    private static function fieldOrderMap(): array
    {
        return [
            'title' => 0,
            'amount' => 1,
            'currency' => 2,
            'date' => 3,
            'user' => 4,
            'regular' => 5,
            'frequency' => 6,
            'total' => 7,
            'credit_percent' => 8,
            'deadline' => 9,
        ];
    }

    private static function getCurrencies(): array
    {
        return cache()->remember('currencies', now()->addDay(), fn() => config('currencies'));
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'normalized_title' => $this->normalized_title,
            'slug' => $this->slug,
            'regular' => $this->regular,
            'frequency' => $this->frequency,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total' => $this->total,
            'total_paid' => $this->total_paid,
            'total_due' => $this->total_due,
            'date' => $this->date,
            'credit_percent' => $this->credit_percent,
            'deadline' => $this->deadline,

            'user_id' => $this->user_id,
            'budget_id' => $this->budget_id,

            'budget' => new BudgetResource($this->whenLoaded('budget')),
        ];
    }
}
