<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'account' => ['required'],
            'user' => ['required'],
            'source' => ['required'],
            'amount' => ['required', 'numeric'],
            'created_at' => ['date'],
            'date' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
