<?php

namespace App\Http\Requests;

use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'source' => ['required', 'integer', 'exists:accounts,id'],
            'currency' => ['required', new ValidCurrencyRule()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
