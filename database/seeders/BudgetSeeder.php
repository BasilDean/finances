<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $budgets = array(
            ['title' => 'Dandelions'],
            ['title' => 'Tests'],
            ['title' => 'Budget', 'currency' => "USD"],
        );

        $user1 = User::find(1);
        $user2 = User::find(2);
        foreach ($budgets as $budget) {
            Budget::create($budget);
        }

        $budget1 = Budget::find(1);
        $user1->budgets()->save($budget1);
        $user2->budgets()->save($budget1);
        $budget2 = Budget::find(2);
        $user1->budgets()->save($budget2);
    }
}
