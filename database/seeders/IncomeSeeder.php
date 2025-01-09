<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/seeders/csv/incomes.csv'), 'rb');
        $header = fgetcsv($csvFile);

        while ($row = fgetcsv($csvFile)) {
            $data = array_combine($header, $row);

            // Convert 'NULL' strings to actual null values
            foreach ($data as $key => $value) {
                if ($value === 'NULL') {
                    $data[$key] = null;
                }
            }

            Income::create($data);
        }

        fclose($csvFile);
    }
}
