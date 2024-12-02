<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'normalized_title' => $this->faker->word(),
            'title' => $this->faker->word(),
            'amount' => $this->faker->randomNumber(),
            'currency' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'account_id' => Account::factory(),
            'user_id' => User::factory(),
        ];
    }
}
