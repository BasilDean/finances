<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Exchange;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExchangeFactory extends Factory
{
    protected $model = Exchange::class;

    public function definition(): array
    {
        $amount_to = $this->faker->randomFloat();
        $amount_from = $amount_to * $this->faker->randomFloat();
        $exchange_rate = $amount_from / $amount_to;
        return [
            'amount_to' => $amount_to,
            'currency_from' => collect(config('currencies'))->random(),
            'currency_to' => collect(config('currencies'))->random(),
            'exchange_rate' => $exchange_rate,
            'amount_from' => $amount_from,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'account_from' => Account::factory(),
            'account_to' => Account::factory(),
        ];
    }
}
