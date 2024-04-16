<?php


namespace Modules\CurrencyRate\Services;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Modules\CurrencyRate\Models\CurrencyRate;
use Modules\CurrensyAdd\Models\Currensy;

class CurrencyRateService
{
    protected $client;
    protected $api_key;
    const BASE_CURENCY = 'USD';
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->api_key = config('currencyrate.api_key');
    }

    public function generateISO8601Today() {
        $today = Carbon::today();
       return $today->subYear();
    }

    public function generateISO8601Dates($numDays) {
        $dates = array();
        $today = Carbon::today();
        $today->subYear();
        $dates[] = $today->toIso8601String();
        for ($i = 1; $i < $numDays; $i++) {
            $date = $today->subDays(1)->toIso8601String();
            $dates[] = $date;
        }

        return $dates;
    }

    public static function generateISO8601MinDate($numDays) {
        $today = Carbon::today();
        $today->subYear();
        $date = $today->toIso8601String();
        for ($i = 1; $i < $numDays; $i++) {
            $date = $today->subDays(1)->toIso8601String();
        }

        return $date;
    }

    public function getCurrencyRates($base, $quote, $date_time)
    {
        $url = "https://exchange-rates-api.oanda.com/v2/rates/spot.json";
        $parameters = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->api_key,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'base' => $base,
                'quote' => $quote,
                'date_time' => $date_time,
            ],
        ];

        try {
            $response = $this->client->request('GET', $url, $parameters);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data;
        } catch (\Exception $e) {

                dd(['error' => $e->getMessage(),
                'error1' => $e->getTrace(),
                'error2' => $e->getLine()]);
        }
    }

    public static function setterCurrencyRate($base_currensy)
    {
        $client = new Client();
        $isEmptyCurrensy = Currensy::isTableEmpty();
        $currencyRateService = new CurrencyRateService($client);
        if(!$isEmptyCurrensy){
            $allCurrensy = Currensy::getAllCurrensy();
            $dateArray = $currencyRateService->generateISO8601Dates(14);

            $codeArray = [];
            foreach($allCurrensy as $currency){
                $codeArray[] = $currency['code'];
            }

            foreach($dateArray as $date){
                foreach($codeArray as $code){
                    $response =  $currencyRateService->getCurrencyRates($base_currensy,$code,$date);
                    CurrencyRate::createCurrensyRate([
                        'base_currency' => $response['quotes'][0]['base_currency'],
                        'quote_currency' => $response['quotes'][0]['quote_currency'],
                        'date_time' => $response['quotes'][0]['date_time'],
                        'bid' => $response['quotes'][0]['bid'],
                        'ask' => $response['quotes'][0]['ask'],
                        'midpoint' => $response['quotes'][0]['midpoint']
                    ]);
                }
            }
        }else{
            return false;
        }

    }

    public static function daysDifference($latestDate)
    {
        $today = Carbon::today();
        $today->subYear();
        return $today->diffInDays($latestDate);
    }

    public static function setRateDifferenceDays($base_currensy)
    {
        $allCurrensy = Currensy::getAllCurrensy();
        $client = new Client();
        $currencyRateService = new CurrencyRateService($client);
        $codeArray = [];
        foreach ($allCurrensy as $currency) {
            $codeArray[] = $currency['code'];
            $maxDateRateInDB = CurrencyRate::maxDateRate($base_currensy,$currency['code']);
            if(empty($maxDateRateInDB)){
                $maxDateRateInDB = CurrencyRate::minDate();
            }
            $daysDifference = CurrencyRateService::daysDifference($maxDateRateInDB);
            if ($daysDifference > 0) {
                $dateArray = $currencyRateService->generateISO8601Dates($daysDifference);
                foreach ($dateArray as $date) {
                    $response = $currencyRateService->getCurrencyRates($base_currensy, $currency['code'], $date);
                    CurrencyRate::createCurrensyRate([
                        'base_currency' => $response['quotes'][0]['base_currency'],
                        'quote_currency' => $response['quotes'][0]['quote_currency'],
                        'date_time' => $response['quotes'][0]['date_time'],
                        'bid' => $response['quotes'][0]['bid'],
                        'ask' => $response['quotes'][0]['ask'],
                        'midpoint' => $response['quotes'][0]['midpoint']
                    ]);
                }
            }
        }

    }

    public static function getAllCurrencyRateByMinDate($base,$quote,$date)
    {
        if($date=='max'){
            $dateMin = CurrencyRate::minDate();
        }else{
            $dateMin = self::generateISO8601MinDate($date);
        }



       return CurrencyRate::getAllCurrencyRateByMinDate($base,$quote,$dateMin);
    }

}
