<?php

namespace App\Http\Resources;

use App\Models\Budget;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Income */
class IncomeResource extends JsonResource
{

    /**
     * Define the fields shown to the frontend.
     *
     * @param string|null $context The context for the fields (e.g., 'edit', 'show').
     * @return array The field definitions for the given context.
     */
    public static function getFields(?string $context = null): array
    {
        $budget = Budget::where('slug', 'LIKE', auth()->user()->settings->active_budget)->first();
        $accounts = $budget->accounts;
        $users = $budget->users;
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text']),
            'amount' => self::makeField('number', 'amount', false, ['filter' => true, 'filter-type' => 'text']),
            'user' => self::makeField('relation', 'user', false,
                ['values' => $users, 'multiple' => false, 'showField' => 'name']),
            'source' => self::makeField('text', 'source', false,
                ['filter' => true, 'filter-type' => 'text']),

            'account' => self::makeField('relation', 'account', false, ['values' => $accounts, 'multiple' => false, 'showField' => 'title']),
            'date' => self::makeField('date', 'date', false),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [
                'type' => self::makeField('list', 'type', false, ['values' => ['cash', 'account']]),
            ],
            'show' => [
                'currency' => self::makeField('list', false, 3, ['values' => self::getCurrencies(), 'filter' => true, 'filter-type' => 'select']),
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

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'source' => $this->source,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'normalized_title' => $this->normalized_title,
            'slug' => $this->slug,
            'date' => $this->date,

            'account' => new AccountResource($this->whenLoaded('account')),
        ];
    }
}
