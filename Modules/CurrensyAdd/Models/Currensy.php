<?php


namespace Modules\CurrensyAdd\Models;


use Illuminate\Database\Eloquent\Model;

class Currensy extends Model
{
    protected $fillable = [
        'code',
        'description',
    ];

    protected $table = 'currencies';

    /**
     * Saving a value in a model
     * $data =  ['code' => 'USDT', 'description' => 'USDT COIN']
     * @param array $data
     * @return Currensy
     */
    public static function createCurrensy(array $data)
    {
        return self::create($data);
    }

    /**
     * Getting all model values
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCurrensy()
    {
        return self::all();
    }

    /**
     * Getting model value by ID
     *
     * @param int $id
     * @return Currensy|null
     */
    public static function getCurrensyById(int $id)
    {
        return self::find($id);
    }

    /**
     * Checking the existence of records with specified conditions
     * $conditions == ['code' => 'USD']
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
}
