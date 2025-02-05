<?php

namespace App\Http\Requests;

use App\Models\Setting;
use App\Rules\ValidCurrencyRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'default_currency' => ['required', new ValidCurrencyRule()],
        ];
    }

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('setting'))->or($this->user()->can('create', Setting::class));
    }
}
