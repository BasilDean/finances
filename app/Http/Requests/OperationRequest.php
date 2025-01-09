<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts'],
            'amount' => ['required', 'numeric'],
            'operation_type' => ['required'],
            'description' => ['nullable'],
            'balance_after' => ['required', 'numeric'],
            'performed_at' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
