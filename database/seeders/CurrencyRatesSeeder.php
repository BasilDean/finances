<?php

namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;

class CurrencyRatesSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = fopen(base_path('database/seeders/csv/currency_rates.csv'), 'r');
        $header = fgetcsv($csvFile);

        while ($row = fgetcsv($csvFile)) {
            $data = array_combine($header, $row);

            // Convert 'NULL' strings to actual null values
            foreach ($data as $key => $value) {
                if ($value === 'NULL') {
                    $data[$key] = null;
                }
            }

            CurrencyRate::create($data);
        }

        fclose($csvFile);
    }
}
