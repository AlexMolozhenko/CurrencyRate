<?php

namespace Modules\CurrensyAdd\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CurrencyRate\Services\CurrencyRateService;
use Modules\CurrensyAdd\Models\Currensy;
use Modules\CurrensyAdd\Services\CurrencyService;

class CurrensyAddController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $client = new Client();
        $currencyService = new CurrencyService($client);

        $filteredData = [];
        $responseCurrency = $currencyService->getAllCurrency();
        $localCurrency =  Currensy::getAllCurrensy();
        foreach($responseCurrency['currencies'] as $item){
            $codeExists = false;
            foreach ($localCurrency as $dbCurrency) {
                if ($item["code"] === $dbCurrency["code"]) {
                    $codeExists = true;
                    break;
                }
            }
            if (!$codeExists) {
                $filteredData[] = $item;
            }
        }
        return $filteredData;
    }



    public function saveNewCurrency(Request $request)
    {
        $allNewCurrensy = $request->toArray();
        foreach($allNewCurrensy as $currency){
            if(isset($currency['code'])) {
                $existCurrency =  Currensy::exists(['code' => $currency['code']]);
                if(!$existCurrency){
                    Currensy::createCurrensy($currency);
                }
            }else{
                return false;
            }
        }
        return true;
    }

}
