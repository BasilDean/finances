<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'account' => ['required'],
            'user' => ['required'],
            'title' => ['required'],
            'amount' => ['required', 'integer'],
            'currency' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
