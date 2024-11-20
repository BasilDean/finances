<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BudgetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('budgets', 'slug')->ignore($this->budget)
            ],
            'main_currency' => ['required', new ValidCurrencyRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
