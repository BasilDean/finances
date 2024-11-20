<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(),
            'source' => Account::inRandomOrder()->first()->id,
            'currency' => $this->faker->currencyCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
