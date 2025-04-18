<?php

namespace App\Http\Requests;

use App\Models\PurchaseItem;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'purchase_id' => ['required', 'exists:purchases'],
            'title' => ['required'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', PurchaseItem::class)->or($this->user()->can('update', $this->route('purchaseItem')));
    }
}
