<?php

namespace App\Http\Resources;

use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/** @mixin PurchaseItem */
class PurchaseItemResource extends JsonResource
{

    /**
     * Define the fields shown to the frontend.
     *
     * @param string|null $context The context for the fields (e.g., 'edit', 'show').
     * @return array The field definitions for the given context.
     */
    public static function getFields(?string $context = null): array
    {
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text', 'show' => true]),
            'quantity' => self::makeField('number', 'quantity', false, ['show' => true]),
            'price' => self::makeField('number', 'price', false, ['show' => true]),
            'id' => self::makeField('number', 'id', false, ['editable' => false]),
            'expense_id' => self::makeField('number', 'expense_id', false, ['editable' => false]),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' => [],
            'show' => [],
            'admin' => [],
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
            'price' => 1,
            'quantity' => 2,
            'id' => 3,
            'expense_id' => 4,
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'normalized_title' => $this->normalized_title,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'expense_id' => $this->expense_id,

            'expense' => new ExpenseResource($this->whenLoaded('expense')),
        ];
    }
}
