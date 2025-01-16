<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code_from',
        'code_to',
        'rate',
    ];

    public function convertToRubbles($source, $amount)
    {
        if ($source === 'RUB') {
            return $amount;
        }
        return round($amount / self::where('code_from', 'RUB')->where('code_to', $source)->first()->rate, 2);
    }

    public function convertToCurrency($destination, $amount)
    {
        return round($amount * self::where('code_from', 'RUB')->where('code_to', $destination)->first()->rate, 2);
    }
}
