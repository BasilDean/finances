<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'main_currency' => ['required', new ValidCurrencyRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
