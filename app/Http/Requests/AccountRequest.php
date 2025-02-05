<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'currency' => ['required', new ValidCurrencyRule()],
            'type' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('create', Account::class)->or($this->user()->can('update', $this->route('account')));
    }
}
