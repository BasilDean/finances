<?php

namespace App\Http\Requests;

use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'normalized_title' => ['required'],
            'slug' => ['required'],
            'regular' => ['boolean'],
            'frequency' => ['nullable'],
            'amount' => ['required'],
            'currency' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Payment::class)->or($this->user()->can('update', $this->route('payment')));
    }
}
