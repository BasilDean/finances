<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PurchaseItemFactory extends Factory
{
    protected $model = PurchaseItem::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'normalized_title' => $this->faker->word(),
            'price' => $this->faker->randomFloat(),
            'quantity' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'purchase_id' => Purchase::factory(),
        ];
    }
}
