<?php

namespace App;
use Config;
use DB;
use Illuminate\Database\Eloquent\Model;

class RedeemCode extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "redeem_code";
    
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
       'vendor_id','redeem_code','mobile_number','status'
    ];

    public static function saveRedeemDetails( $data, $redeemCode, $merMobileNumber){
         
        $RedeemDetail = new RedeemCode();
        $RedeemDetail->vendor_id = $data['vendor_id'];
        $RedeemDetail->user_id = $data['user_id'];
        $RedeemDetail->loyalty_id = $data['loyalty_id'];
        $RedeemDetail->mobile_number = $data['mobile_number'];
        $RedeemDetail->mer_mobile_number = $merMobileNumber;
        $RedeemDetail->redeem_code = ( ( isset( $redeemCode ) && !empty( $redeemCode ) ) ? $redeemCode : '' );
        $RedeemDetail->status = Config::get('constant.NUMBER.ZERO');
        $RedeemDetail->save();

        return ( isset($RedeemDetail->id) && !empty($RedeemDetail->id) ? $RedeemDetail->id : '' );
    }

    public static function getUserRedeemDetails( $userId = NULL ) {
        
        $query  = DB::table('merchant_details as m')
                  ->select("m.company_name","m.mobile_number","m.vendor_id","m.description","l.max_checkin","l.max_bill_amount","l.zoin_point","l.loyalty_id","l.loyalty_status","r.redeem_code")
                  ->join('loyalty as l', 'm.vendor_id', '=', 'l.vendor_id')
                  ->join('redeem_code as r', 'l.vendor_id', '=', 'r.vendor_id')
                  ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                  //->where(['r.status' => Config::get('constant.NUMBER.ONE')])
                  ->orderBy('m.id', 'DESC');
         
               if($userId) {
                    $query->where('r.user_id', '=', $userId);
               }

               $data = $query->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

    public static function isCheckRedeemLoyaltyCount( $data ) {
        
        $count = RedeemCode::where(['vendor_id' => $data['vendor_id'], 'user_id' => $data['user_id'],'loyalty_id' => $data['loyalty_id'],'mobile_number' => $data['mobile_number']])->count();
        
        return ( isset( $count ) && !empty( $count ) ? $count : '' );
    }

    public static function updateRedeemCode( $data ){
        
        $redeemCode = RedeemCode::where("redeem_code", '=', $data['redeem_code'])->select('id','redeem_code','mobile_number','status')->first();
        $redeemCode->status = Config::get('constant.NUMBER.ONE');
        $redeemCode->save();

        return ( isset($redeemCode->id) && !empty($redeemCode->id) ? $redeemCode->id : '' );          
    }
    
    public static function getUserTransactionDetails( $data = NULL ) {
           
        $query  = DB::table('user_details as u')
                  ->select("u.user_id","u.full_name","u.user_level","u.mobile_number","l.max_checkin","l.max_bill_amount","l.zoin_point","l.loyalty_id","l.loyalty_status","r.vendor_id","r.redeem_code","r.user_checkin","r.user_balance","zb.zoin_balance as merBalance")
                  ->join('redeem_code as r', 'u.mobile_number', '=', 'r.mobile_number')
                  //->join('loyalty as l', 'r.vendor_id', '=', 'l.vendor_id')
                  ->join('loyalty as l', 'r.loyalty_id', '=', 'l.loyalty_id')
                  ->leftjoin('zoin_balance as zb', 'r.vendor_id', '=', 'zb.vendor_or_user_id')
                  ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                  ->orderBy('u.id', 'DESC');
         
               if($data) {
                    $query->where('r.vendor_id', '=', $data['vendor_id'] );
                    $query->where('r.user_id', '=', $data['user_id'] );
                    $query->where('r.loyalty_id', '=', $data['loyalty_id'] );
               }

               $records = $query->get();
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );
    
    }


    public static function getUserTransactions( $redeemCode = NULL ) {
        
        $query  = DB::table('user_details as u')
                  ->select("u.user_id","u.full_name","u.user_level","u.mobile_number","l.max_checkin","l.max_bill_amount",
                  "l.zoin_point","l.loyalty_id","l.loyalty_status","r.vendor_id","r.redeem_code","r.user_checkin","r.user_balance","zb.zoin_balance as merBalance")
                  ->join('redeem_code as r', 'u.mobile_number', '=', 'r.mobile_number')
                  ->join('loyalty as l', 'r.loyalty_id', '=', 'l.loyalty_id')
                  ->leftjoin('zoin_balance as zb', 'r.vendor_id', '=', 'zb.vendor_or_user_id')
                  ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                  ->orderBy('u.id', 'DESC');
         
               if($redeemCode) {
                    $query->where('r.redeem_code', '=', $redeemCode );
               }

               $data = $query->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }


    public static function getUserCheckInIncrement( $data ) { 
        
       DB::table('redeem_code')->where(['vendor_id' => $data['vendor_id']])->where(['user_id' => $data['user_id']])->where(['loyalty_id' => $data['loyalty_id']])->increment('user_checkin');
       $redeemCode = RedeemCode::where(['vendor_id' => $data['vendor_id']])->where(['user_id' => $data['user_id']])->where(['loyalty_id' => $data['loyalty_id']])->first();
       
       return ( isset( $redeemCode['user_checkin'] ) && !empty( $redeemCode['user_checkin'] ) ? $redeemCode['user_checkin'] : '' );
    }

    public static function userRedeemCodeCount( $mobileNumber ){

        $data = RedeemCode::where(['mobile_number' => $mobileNumber ])->count();

        return ( isset( $data ) && !empty( $data ) ? $data : '' ); 
    }

    public static function getUserRedeemedDetails( $mobileNumber = NULL ) {
  
        $query  = DB::table('redeem_code as r')
                   ->select("m.company_name","m.mobile_number","l.max_checkin","l.max_bill_amount","l.zoin_point","l.loyalty_status","r.vendor_id","r.user_id","r.loyalty_id","r.redeem_code")
                   ->join('merchant_details as m', 'r.vendor_id', '=', 'm.vendor_id')
                   ->join('user_details as u', 'r.user_id', '=', 'u.user_id')
                   ->leftjoin('loyalty as l', 'r.loyalty_id', '=', 'l.loyalty_id')
                   ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                   ->orderBy('m.id', 'DESC');
        
           if($mobileNumber) {
                   $query->where('r.mobile_number', '=', $mobileNumber);
           }

           $data = $query->get();
       
       return ( isset( $data ) && !empty( $data ) ? $data : '' );

   }
 
   public static function userRedeemedCodeCount( $data ){

        $recordes = RedeemCode::where(['redeem_code' => $data->redeem_code ])->first();

        return ( isset( $recordes ) && !empty( $recordes ) ? $recordes : '' ); 
    }

    public static function userLoyaltyBalanceIncrement( $data, $balance ) {
        
        //$records = LoyaltyBalance::where(['user_id' => $userId])->increment('user_balance',$balance);
        $records = DB::table('redeem_code')->where(['vendor_id' => $data[0]->vendor_id])->where(['user_id' => $data[0]->user_id])->where(['loyalty_id' => $data[0]->loyalty_id])->update(array('user_balance' => $balance));
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );

    }

    public static function userLoyaltyBalanceDecrement( $data, $balance ) {
      
        $balance = DB::table('redeem_code')->where(['vendor_id' => $data[0]->vendor_id])->where(['user_id' => $data[0]->user_id])->where(['loyalty_id' => $data[0]->loyalty_id])->update(array('user_balance' => $balance));
      
        return ( isset( $balance->id ) && !empty( $balance->id ) ? $balance->id : '' );

    }

    public static function isCheckMerchantRedeemCode( $data ) {
        
        $records = RedeemCode::where(['mer_mobile_number' => $data['mobile_number'], 'redeem_code' => $data['redeem_code']])->first();
        
        return ( isset( $records ) && !empty( $records ) ? $records : '' );
    }


}
