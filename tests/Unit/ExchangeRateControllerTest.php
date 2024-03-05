<?php


namespace Tests\Unit;

use App\Http\Controllers\ExchangeRateController;
use App\Models\ExchangeRate;
use App\Services\CbrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ExchangeRateControllerTest extends TestCase
{
    private function serializeExchangeRates($exchangeRates)
    {
        return array_map(function ($exchangeRate) {
            return $exchangeRate->toArray();
        }, $exchangeRates);
    }

    public function testFetchAndStoreExchangeRatesSuccess()
    {
        // Mock CbrService
        $cbrServiceMock = $this->getMockBuilder(CbrService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $exchangeRatesData = [
            ['currency_code' => 'USD', 'exchange_rate' => 75.50, 'date' => '2024-03-05'],
            ['currency_code' => 'EUR', 'exchange_rate' => 90.25, 'date' => '2024-03-05'],
        ];

        $cbrServiceMock->expects($this->once())
            ->method('fetchExchangeRates')
            ->willReturn($exchangeRatesData);

        $this->app->instance(CbrService::class, $cbrServiceMock);

        Log::shouldReceive('info')->once()->andReturnNull();
        Log::shouldReceive('error')->never();


        $response = $this->json('GET', '/api/exchange-rates');

        $response->assertStatus(JsonResponse::HTTP_OK)
            ->assertJson([
                'message' => 'Exchange rates fetched and stored successfully',
                'data' => $exchangeRatesData,
            ]);
    }

    public function testFetchAndStoreExchangeRatesError()
    {
        $cbrServiceMock = $this->getMockBuilder(CbrService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cbrServiceMock->expects($this->once())
            ->method('fetchExchangeRates')
            ->willThrowException(new \Exception('Service unavailable'));

        $this->app->instance(CbrService::class, $cbrServiceMock);


        Log::shouldReceive('info')->never();
        Log::shouldReceive('error')->once()->andReturnNull();


        $response = $this->json('GET', '/api/exchange-rates');

        $response->assertStatus(JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson(['error' => 'Failed to fetch and store exchange rates.']);
    }

}
