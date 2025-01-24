<?php

namespace App\Http\Resources;

use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Exchange */
class ExchangeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount_from' => $this->amount_from,
            'currency_from' => $this->currency_from,
            'amount_to' => $this->amount_to,
            'currency_to' => $this->currency_to,
            'exchange_rate' => $this->exchange_rate,
            'oficial_rate' => $this->oficial_rate,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date' => $this->date,

            'account_from' => $this->account_from,
            'account_to' => $this->account_to,
            'user_id' => $this->user_id,
            'expense_id' => $this->expense_id,
            'income_id' => $this->income_id,

            'accountFrom' => new AccountResource($this->whenLoaded('accountFrom')),
            'accountTo' => new AccountResource($this->whenLoaded('accountTo')),
            'expense' => new ExpenseResource($this->whenLoaded('expense')),
            'income' => new IncomeResource($this->whenLoaded('income')),
        ];
    }
}
