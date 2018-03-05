<?php

namespace App;
use Config;
use Illuminate\Database\Eloquent\Model;

class MerchantPoint extends Model
{
     /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "zoin_open_balance";
    
    protected $primaryKey = 'id';
    
    /**
    * The table associated with the model timestamp.
    *
    * @var string
    */
    public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'user_type','amount'
    ];

    public static function getMerchantPoints( ){
        
        $data = MerchantPoint::where("user_type", '=', Config::get('constant.VENDOR'))->select('id','user_type','amount')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

}
