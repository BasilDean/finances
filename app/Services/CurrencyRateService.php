<?php

namespace App\Services;

use App\Models\CurrencyRate;

class CurrencyRateService
{

    public function convertToRubbles($source, $amount): float
    {
        if ($source === 'RUB') {
            return $amount;
        }
        return round($amount / CurrencyRate::where('code_from', 'RUB')->where('code_to', $source)->first()->rate, 2);
    }

    public function convertToCurrency($destination, $amount): float
    {
        return round($amount * CurrencyRate::where('code_from', 'RUB')->where('code_to', $destination)->first()->rate, 2);
    }
}
