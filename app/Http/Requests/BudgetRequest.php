<?php

namespace App\Http\Requests;

use App\Models\Budget;
use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;

class BudgetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'balance' => ['nullable', 'numeric'],
            'currency' => ['required', new ValidCurrencyRule()],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Budget::class)->or($this->user()->can('update', $this->route('budget')));
    }
}
