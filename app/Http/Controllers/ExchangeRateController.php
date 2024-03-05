<?php

namespace App\Http\Controllers;

use App\Services\CbrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\ExchangeRate;

class ExchangeRateController extends Controller
{
    protected CbrService $cbrService;

    public function __construct(CbrService $cbrService)
    {
        $this->cbrService = $cbrService;
    }

    public function index(): JsonResponse
    {
        $exchangeRates = ExchangeRate::all();
        Log::info('Exchange rates retrieved successfully.');
        return response()->json($exchangeRates);
    }

    public function fetchAndStoreExchangeRates(): JsonResponse
    {
        try {
            $exchangeRatesData = $this->cbrService->fetchExchangeRates();

            foreach ($exchangeRatesData as $exchangeRateData) {
                ExchangeRate::query()->updateOrCreate(
                    ['currency_code' => $exchangeRateData['currency_code'], 'date' => $exchangeRateData['date']],
                    ['exchange_rate' => $exchangeRateData['exchange_rate']]
                );
            }
            Log::info('Exchange rates fetched and stored successfully');

            return response()->json(['message' => 'Exchange rates fetched and stored successfully', 'data' => $exchangeRatesData]);
        } catch (\Exception $e) {

            Log::error('Error fetching and storing exchange rates: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to fetch and store exchange rates.'], 500);
        }
    }
}
