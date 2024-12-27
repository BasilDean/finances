<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount_from' => ['required', 'numeric', 'gt:0'],
//            'account_from' => ['required', 'exists:accounts'],
            'amount_to' => ['required', 'numeric', 'gt:0'],
//            'account_to' => ['required', 'exists:accounts'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
