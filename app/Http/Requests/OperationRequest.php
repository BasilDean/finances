<?php

namespace App\Http\Requests;

use App\Models\Operation;
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
        return $this->user()->can('create', Operation::class)->or($this->user()->can('update', $this->route('operation')));
    }
}
