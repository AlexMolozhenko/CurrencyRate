<?php


namespace Modules\CurrencyRate\Models;


use Illuminate\Database\Eloquent\Model;
use Modules\CurrensyAdd\Models\Currensy;

//class CurrencyRate extends Model
class CurrencyRate extends Model
{

    protected $fillable = [
        'base_currency',
        'quote_currency',
        'date_time',
        'bid',
        'ask',
        'midpoint'
    ];

    protected $table = 'currency_rates';


    /**
     * Saving a value in a model
     * $data = [
        'base_currency' => 'USD',
        'quote_currency' => 'EUR',
        'date_time' => '2024-04-12T12:00:00+00:00',
        'bid' => 1.22,
        'ask' => 1.24,
        'midpoint' => 1.23
        ]
     * @param array $data
     * @return Currensy
     */
    public static function createCurrensyRate(array $data)
    {
        return self::create($data);
    }

    public static function getAllCurrencyRateByMinDate($base,$quote,$date)
    {
        $currencyRates = CurrencyRate::where('base_currency', $base)
            ->where('quote_currency', $quote)
            ->where('date_time', '>=', $date)->orderBy('date_time', 'asc')
            ->get();

        return $currencyRates;
    }

    /**
     * Getting all model values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCurrensyRate()
    {
        return self::all();
    }

    /**
     * Getting model value by ID
     *
     * @param int $id
     * @return Currensy|null
     */
    public static function getCurrensyRateById(int $id)
    {
        return self::find($id);
    }

    /**
     * Checking the existence of records with specified conditions
     * $conditions == [
        'base_currency' => 'USD',
        'quote_currency' => 'EUR'
        ]
     * @param array $conditions
     * @return bool
     */
    public static function exists(array $conditions)
    {
        return self::where($conditions)->exists();
    }

    /**
     * Checks if the table is empty
     *
     * @return bool
     */
    public static function isTableEmpty()
    {
         if(self::count() > 0){
             return false;
         }else{
             return true;
         }
    }

    public static function maxDateRate($baseCurrency,$currency)
    {
        return self::where('quote_currency', $currency)
            ->where('base_currency', $baseCurrency)
            ->max('date_time');
    }

    public static function minDate(){
        return self::min('date_time');
    }
}
