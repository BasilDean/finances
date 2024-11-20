<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCurrencyRule implements ValidationRule
{
    protected $validCurrencies = [
        'USD',
        'RUB',
        'EUR',
        'GEL',
        'TRY',
        'UZS'
    ];
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!in_array($value, $this->validCurrencies, true)) {
            $fail('Currency is not valid.');
        }
    }
}
