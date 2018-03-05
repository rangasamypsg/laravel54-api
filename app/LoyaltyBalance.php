<?php

namespace App;
use Config;
use DB;
use Illuminate\Database\Eloquent\Model;

class LoyaltyBalance extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "loyalty_balance";
    
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
        'vendor_id','user_id','total_loyalty'
    ];


    public static function saveMerchantloyaltyCount( $data ) {
        
        $merchantLoyCount = new LoyaltyBalance();
        $merchantLoyCount->vendor_id = $data['vendor_id'];        
        $merchantLoyCount->total_loyalty = Config::get('constant.NUMBER.ONE');
        $merchantLoyCount->save();

        return ( isset( $merchantLoyCount->id ) && !empty( $merchantLoyCount->id ) ? $merchantLoyCount->id : '' );

    }

    public static function getLoyaltyBalanceDetails( $user_id ) {
        
        $records = LoyaltyBalance::where(['user_id' => $user_id])->first();
        
        return ( isset( $records['total_loyalty'] ) && !empty( $records['total_loyalty'] ) ? $records['total_loyalty'] : '' );

    }

    public static function checkLoyaltyBalance( $data ) {
        
        $records = LoyaltyBalance::where(['user_id' => $data[0]->user_id])->first();
      
        return ( isset( $records ) && !empty( $records ) ? $records : '' );

    }

    public static function checkMerchantLoyaltyBalance( $data ) {
        
        $records = LoyaltyBalance::where(['vendor_id' => $data[0]->vendor_id])->first();
      
        return ( isset( $records ) && !empty( $records ) ? $records : '' );

    }

    /* public static function saveUserLoyaltyBalance( $data ) {
        
        $balanceId = LoyaltyBalance::checkLoyaltyBalance( $data );

        if( isset( $balanceId ) && !empty( $balanceId ) ) {
           
             $update = DB::table('loyalty_balance')->where('user_id', $data[0]->user_id)->increment('total_loyalty');
          
        } else {            
       
            $LoyBalance = new LoyaltyBalance();
            $LoyBalance->user_id = $data[0]->user_id;        
            $LoyBalance->total_loyalty = Config::get('constant.NUMBER.ONE');
            $records = $LoyBalance->save();
        }
        
       $totalLoyalty = LoyaltyBalance::getLoyaltyBalanceDetails( $data[0]->user_id );

        return ( isset( $totalLoyalty ) && !empty( $totalLoyalty ) ? $totalLoyalty : '' );
    } */


    public static function saveUserLoyaltyBalance( $data ) {
        
        $LoyBalance = new LoyaltyBalance();
        $LoyBalance->user_id = $data[0]->user_id;        
        $LoyBalance->total_loyalty = Config::get('constant.NUMBER.ONE');
       // $LoyBalance->user_balance = Config::get('constant.NUMBER.ZERO');
        $records = $LoyBalance->save();
         
        return ( isset( $records->id ) && !empty( $records->id ) ? $records->id : '' );
    }

    public static function saveMerchantLoyaltyBalance( $data ) {
        
        $LoyBalance = new LoyaltyBalance();
        $LoyBalance->vendor_id = $data[0]->vendor_id;
        $LoyBalance->total_loyalty = Config::get('constant.NUMBER.ONE');
       // $LoyBalance->user_balance = Config::get('constant.NUMBER.ZERO');
        $records = $LoyBalance->save();
         
        return ( isset( $records->id ) && !empty( $records->id ) ? $records->id : '' );
    }

    public static function getMerchantloyaltyCount( $vendor_id ) {
        
        $records = LoyaltyBalance::where(['vendor_id' => $vendor_id])->first();
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );

    }

    public static function userLoyaltyBalanceIncrement( $userId, $balance ) {
        
        $records = DB::table('loyalty_balance')->where("user_id", '=', $userId)->update(array('user_balance' => $balance));
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );

    }

    public static function userLoyaltyBalanceDecrement( $userId, $balance ) {
      
        $balance = DB::table('loyalty_balance')->where("user_id", '=', $userId)->update(array('user_balance' => $balance));
      
        return ( isset( $balance->id ) && !empty( $balance->id ) ? $balance->id : '' );

    }

    public static function merchantLoyaltyBalanceIncrement( $vendorId ) {
        
        $records = LoyaltyBalance::where(['vendor_id' => $vendorId])->increment('total_loyalty');
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );

    }


}
