<?php

namespace App\Http\Requests;

use App\Models\Exchange;
use Illuminate\Foundation\Http\FormRequest;

class ExchangeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount_from' => ['required', 'numeric', 'gt:0'],
            'amount_to' => ['required', 'numeric', 'gt:0'],
            'date' => ['required', 'date'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Exchange::class)->or($this->user()->can('update', $this->route('exchange')));
    }
}
