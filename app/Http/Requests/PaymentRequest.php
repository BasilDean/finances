<?php

namespace App\Http\Requests;

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
        return true;
    }
}
