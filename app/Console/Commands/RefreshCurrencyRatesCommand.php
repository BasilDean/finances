<?php

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use App\Services\CurrencyLayerService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class RefreshCurrencyRatesCommand extends Command
{
    protected $signature = 'refresh:currency-rates {sourceCurrency=RUB}';

    protected $description = 'Refresh currency rates from CurrencyLayer API';

    protected CurrencyLayerService $currencyLayerService;

    public function __construct(CurrencyLayerService $currencyLayerService)
    {
        parent::__construct();
        $this->currencyLayerService = $currencyLayerService;
    }

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {

        $sourceCurrency = $this->argument('sourceCurrency');

        $this->info('Refreshing currency rates...');

        $rates = $this->currencyLayerService->getRates($sourceCurrency);

        if (isset($rates['success']) && $rates['success']) {
            foreach ($rates['quotes'] as $currency => $rate) {
                $currency = substr($currency, 3);
                CurrencyRate::updateOrCreate(
                    ['code_from' => $sourceCurrency, 'code_to' => $currency], ['rate' => $rate],
                );
            }

            $this->info('Currency rates refreshed successfully.');
        } else {
            $this->error('Failed to refresh currency rates: ' . $rates['error']['info']);
        }

    }
}
