<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code_from' => ['required', new ValidCurrencyRule()],
            'code_to' => ['required', new ValidCurrencyRule()],
            'rate' => ['required', 'numeric'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
