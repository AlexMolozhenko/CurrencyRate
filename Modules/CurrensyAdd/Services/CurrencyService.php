<?php


namespace Modules\CurrensyAdd\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;

class CurrencyService
{
    protected $client;
    protected $api_key;
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->api_key = config('currencyrate.api_key');
    }


    function generateISO8601Dates($numDays) {
        $dates = array();
        $today = Carbon::today();
        $today = Carbon::create(2023, 10, 11)->startOfDay();
        $dates[] = $today->toIso8601String();
        for ($i = 1; $i < $numDays; $i++) {
//            $date = $today->subDays($i)->toDateString();
            $date = $today->subDays(1)->toIso8601String();
            $dates[] = $date;
        }

        return $dates;
    }

    public function getAllCurrency()
    {
        $url = "https://exchange-rates-api.oanda.com/v2/currencies.json";
        $parameters = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->api_key,
                'Content-Type' => 'application/json',
            ]
        ];

        try {
            $response = $this->client->request('GET', $url, $parameters);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (\Exception $e) {
            // Обработка ошибок
            return [
                'error' => $e->getMessage(),
                'error1' => $e->getTrace(),
                'error2' => $e->getLine()
            ];
        }
    }

}
