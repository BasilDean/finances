<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/seeders/csv/budgets.csv'), 'rb');
        $header = fgetcsv($csvFile);

        while ($row = fgetcsv($csvFile)) {
            $data = array_combine($header, $row);

            // Convert 'NULL' strings to actual null values
            foreach ($data as $key => $value) {
                if ($value === 'NULL') {
                    $data[$key] = null;
                }
            }

            $budget = Budget::create($data);

            $users = User::all();
            $budget->users()->attach($users);
        }
        $budget = Budget::find(1);

        if (!$budget) {
            // Handle the case where the budget is not found (null is returned).
            // For example:
            throw new RuntimeException('Budget not found.');
        }
        $users = User::all();
        foreach ($users as $user) {
            $user->settings()->create([
                'active_budget' => $budget->slug,
            ]);
        }


        fclose($csvFile);
    }
}
