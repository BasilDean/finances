<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/seeders/csv/expenses.csv'), 'rb');
        $header = fgetcsv($csvFile);

        while ($row = fgetcsv($csvFile)) {
            $data = array_combine($header, $row);

            // Convert 'NULL' strings to actual null values
            foreach ($data as $key => $value) {
                if ($value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Expense::create($data);
        }

        fclose($csvFile);


        $csvFile = fopen(base_path('database/seeders/csv/category_expense.csv'), 'rb');
        $header = fgetcsv($csvFile);

        while ($row = fgetcsv($csvFile)) {
            $data = array_combine($header, $row);

            // Convert 'NULL' strings to actual null values
            foreach ($data as $key => $value) {
                if ($value === 'NULL') {
                    $data[$key] = null;
                }
            }

            // Ensure Category model and relationship are defined as needed
            $expense = Expense::find($data['expense_id']);
            $category = Category::find($data['category_id']);

            if ($expense && $category) {
                $expense->categories()->attach($category);
            }
        }
        fclose($csvFile);
    }
}
