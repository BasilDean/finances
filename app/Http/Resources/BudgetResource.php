<?php

namespace App\Http\Resources;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Budget */
class BudgetResource extends JsonResource
{
    /**
     * Define the fields shown to the frontend.
     *
     * @param string|null $context The context for the fields (e.g., 'edit', 'show').
     * @return array The field definitions for the given context.
     */
    protected static function getFields(?string $context = null): array
    {
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text']),
        ];
        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [
                'currency' => self::makeField('list', false, 3, ['values' => self::getCurrencies(), 'filter' => true, 'filter-type' => 'select']),
            ],
            'show' => [
                'balance' => self::makeField('number', 'balance', false),
            ],
            default => [],
        };

        // Return merged fields sorted by the 'order' field
        return collect(array_merge($defaultFields, $contextFields))
            ->sortBy('order')
            ->toArray();
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
            'currency' => 1,
            'type' => 2,
            'amount' => 3,
        ];
    }

    private static function getCurrencies(): array
    {
        return cache()->remember('currencies', now()->addDay(), fn() => config('currencies'));
    }


    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array The transformed resource attributes and relationship counts.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'accounts_count' => $this->accounts_count,
            'history_count' => $this->history_count,
            'payments_count' => $this->payments_count,
            'users_count' => $this->users_count,

            'accounts' => AccountResource::collection($this->whenLoaded('accounts')),
        ];
    }
}
