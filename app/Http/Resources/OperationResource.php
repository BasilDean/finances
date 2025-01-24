<?php

namespace App\Http\Resources;

use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Operation */
class OperationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'operation_type' => $this->operation_type,
            'operation_id' => $this->operation_id,
            'description' => $this->description,
            'balance_after' => $this->balance_after,
            'performed_at' => $this->performed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'account_id' => $this->account_id,

            'account' => new AccountResource($this->whenLoaded('account')),
        ];
    }
}
