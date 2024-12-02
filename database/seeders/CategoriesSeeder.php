<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/seeders/csv/categories.csv'), 'r');
        $header = fgetcsv($csvFile);

        while ($row = fgetcsv($csvFile)) {
            $categoryData = array_combine($header, $row);

            // Convert 'NULL' strings to actual null values
            foreach ($categoryData as $key => $value) {
                if ($value === 'NULL') {
                    $categoryData[$key] = null;
                }
            }

            Category::create($categoryData);
        }

        fclose($csvFile);
    }
}
