<?php

namespace App\Http\Resources;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Expense */
class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'normalized_title' => $this->normalized_title,
            'title' => $this->title,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'has_items' => $this->has_items,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'slug' => $this->slug,
            'amount_calculated' => $this->amount_calculated,
            'date' => $this->date,
            'type' => $this->type,
            'categories_count' => $this->categories_count,
            'exchanges_count' => $this->exchanges_count,
            'items_count' => $this->items_count,

            'account_id' => $this->account_id,
            'user_id' => $this->user_id,

            'account' => new AccountResource($this->whenLoaded('account')),
        ];
    }
}
