<?php

namespace App\Http\Resources;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Log;

/** @mixin Account */
class AccountResource extends JsonResource
{
    /**
     * Define the fields shown to the frontend.
     *
     * @param string|null $context The context for the fields (e.g., 'edit', 'show').
     * @return array The field definitions for the given context.
     */
    public static function getFields(?string $context = null): array
    {
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text']),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [
                'type' => self::makeField('list', 'type', false, ['values' => ['cash', 'account']]),
                'currency' => self::makeField('list', false, 3, ['values' => self::getCurrencies(), 'filter' => true, 'filter-type' => 'select']),
            ],
            'show' => [
                'amount' => self::makeField('number', 'amount', false),
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
            'amount' => $this->amount,
            'currency' => $this->currency,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'normalized_title' => $this->normalized_title,
            'counts' => $this->includeCounts($request),
        ];
    }

    private function includeCounts(Request $request): array
    {
        if (!$request->has('include_counts')) {
            return [];
        }

        return [
            'expenses_count' => $this->expenses_count,
            'incomes_count' => $this->incomes_count,
        ];
    }

}
