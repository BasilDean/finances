<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CurrencyLayerService
{
    protected Client $client;
    protected string $accessToken;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->accessToken = config('app.CURRENCY_LAYER_ACCESS_KEY');
    }

    /**
     * @throws GuzzleException
     */
    public function getRates(string $sourceCurrency = 'RUB', string $targetCurrencies = 'USD,EUR,RUB,GEL,TRY,UZS')
    {
        $response = $this->client->request('GET', 'http://apilayer.net/api/live', [
            'query' => [
                'access_key' => $this->accessToken,
                'source' => $sourceCurrency,
                'currencies' => $targetCurrencies, // Add more currencies as needed
                'format' => 0
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
