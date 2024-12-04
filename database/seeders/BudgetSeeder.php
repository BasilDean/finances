<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/seeders/csv/budgets.csv'), 'r');
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


        fclose($csvFile);
    }
}
