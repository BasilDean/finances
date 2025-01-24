<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
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
