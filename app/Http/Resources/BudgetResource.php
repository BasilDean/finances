<?php

namespace App\Http\Resources;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Budget */
class BudgetResource extends JsonResource
{
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
