<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class CbrService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws GuzzleException
     * @throws \Exception
     */
    public function fetchExchangeRates(): array
    {
        try {
            $response = $this->client->get(config('cbr.exchange_rate_url'));
            return $this->parseExchangeRatesXml($response->getBody()->getContents());
        } catch (\Exception $e) {
            Log::error('Error fetching exchange rates: ' . $e->getMessage());
            throw $e;
        }
    }

    private function parseExchangeRatesXml($xml): array
    {
        $exchangeRates = [];

        $xmlObject = simplexml_load_string($xml);
        if ($xmlObject !== false) {
            foreach ($xmlObject->Valute as $valute) {
                try {
                    $currencyCode = (string)$valute->CharCode;
                    $exchangeRate = (float)str_replace(',', '.', $valute->Value);
                    $date = date('Y-m-d');

                    $exchangeRates[] = [
                        'currency_code' => $currencyCode,
                        'exchange_rate' => $exchangeRate,
                        'date' => $date,
                    ];
                    Log::debug("Parsed exchange rate: Currency Code: $currencyCode, Exchange Rate: $exchangeRate, Date: $date");
                } catch (\Exception $e) {
                    Log::error('Error parsing exchange rates XML: ' . $e->getMessage());
                }
            }
        } else {
            Log::error('Failed to parse exchange rates XML');
        }

        return $exchangeRates;
    }
}
