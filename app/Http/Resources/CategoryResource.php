<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public static function getFields(?string $context = null): array
    {
        // Default fields (shared across contexts)
        $defaultFields = [
            'title' => self::makeField('string', 'title', false, ['filter' => true, 'filter-type' => 'text']),
            'sort' => self::makeField('number', 'sort'),
            'parent_id' => self::makeField('relation', 'parent_id', false, [
                'values' => Category::all(),
                'multiple' => true,
                'showField' => 'title',
                'canBeEmpty' => true]),
        ];

        // Context-specific fields
        $contextFields = match ($context) {
            'edit' =>
            [],
            'show' => [
                'usage_count' => self::makeField('number', 'usage_count'),
                'children_count' => self::makeField('number', 'usage_count'),
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
            'sort' => 1,
            'parent_id' => 2,
            'usage_count' => 3,
            'children_count' => 4,
        ];
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'normalized_title' => $this->normalized_title,
            'parent_id' => $this->parent_id,
            'usage_count' => $this->usage_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sort' => $this->sort,
            'slug' => $this->slug,
            'expenses_count' => $this->expenses_count,

            'expenses' => ExpenseResource::collection($this->whenLoaded('expenses')),
        ];
    }
}
