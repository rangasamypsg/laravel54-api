<?php

namespace App;
use Config;
use Illuminate\Database\Eloquent\Model;

class ZoinBalance extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "zoin_balance";
    
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
        'vendor_or_user_id','zoin_balance'
    ];


    public static function getUserBalance( $data ) {
        
        $records = ZoinBalance::where(['vendor_or_user_id' => $data[0]->user_id])->first();
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );
    }

    public static function merchantZoinBalanceCreate( $vendorId, $amount ){

        $merchant = new ZoinBalance();
        $merchant->vendor_or_user_id = $vendorId;
        $merchant->zoin_balance = $amount;                  
        $merchant->save();

        return ( isset($merchant->id) && !empty($merchant->id) ? $merchant->id : '' );
    }


    public static function getMerchantBalance( $vendorId ) {
        
        $records = ZoinBalance::where(['vendor_or_user_id' => $vendorId])->first();
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );
    }
 
}
