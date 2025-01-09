<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'has_items' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
