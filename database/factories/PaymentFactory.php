<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'normalized_title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'regular' => $this->faker->boolean(),
            'frequency' => $this->faker->word(),
            'amount' => $this->faker->word(),
            'currency' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
