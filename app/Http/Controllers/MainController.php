<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CurrencyRate\Models\CurrencyRate;
use Modules\CurrencyRate\Services\CurrencyRateService;
use Modules\CurrensyAdd\Models\Currensy;


class MainController extends Controller
{
    public function index(){
        $isEmptyCurrencyRate = CurrencyRate::isTableEmpty();

        if($isEmptyCurrencyRate){
           CurrencyRateService::setterCurrencyRate(CurrencyRateService::BASE_CURENCY);
        }else{
            CurrencyRateService::setRateDifferenceDays(CurrencyRateService::BASE_CURENCY);
        }
        $data = [
            'currency'=>Currensy::getAllCurrensy()
        ];
        return view('index', $data);
    }

}
