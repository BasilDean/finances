<?php

namespace App\Http\Resources;

use App\Models\Budget;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin Operation */
class OperationResource extends JsonResource
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
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'description', false, ['filter' => true, 'filter-type' => 'text']),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [],
            'show' => [
                'amount' => self::makeField('number', 'amount', false),
                'date' => self::makeField('date', 'date', false),
            ],
            default => [],
        };

        // Return merged fields
//        return array_merge($defaultFields, $contextFields);
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
            'description' => 0,
            'amount' => 1,
            'date' => 2,
        ];
    }

    public function toArray(Request $request): array
    {
        // Transform a single operation model
        return [
            'title' => $this->description,
            'amount' => $this->operation_type->name === 'income' ? $this->amount : -1 * $this->amount,
            'currency' => $this->account->currency,
            'created_at' => $this->created_at->format('H:i d-m-Y'),
            'date' => $this->performed_at->format('H:i d-m-Y'),
            'account' => $this->account->title,
            'slug' => $this->operation_id,
            'kind' => $this->operation_type->name,
        ];
    }
}
