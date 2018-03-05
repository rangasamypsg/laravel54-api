<?php

namespace App;
use DB;
use Config;
use App\Helpers\CustomHelper;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "notifications";

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
        'subject', 'subject_id', 'user_id', 'image', 'message', 'amount', 'created_at'
    ];

    public static function saveLoginNotification( $data ) {
        
        $notification = new Notification();
        //$notification->transaction_id = CustomHelper::__codeGeneration(Config::get('constant.ZOINUSER.NOTIFICATION'),Config::get('constant.FORMAT-CODE.NOTIFICATION_CODE'));
        $notification->user_id = $data->vendor_id;
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.LOGIN');        
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.LOGIN'), $data->vendor_id);
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }

    public static function saveLogoutNotification( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data->vendor_id;        
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.LOGOUT');        
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.LOGOUT'), $data->vendor_id);
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }  

    public static function saveLoyaltyNotificationDetail( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data['vendor_id'];        
        $notification->subject_id = $data['loyalty_id'];
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.LOYALTY_SUBMIT');         
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.LOYALTY_SUBMIT'), $data['vendor_id'], $data['loyalty_id']);
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }   
    
    public static function saveLoyaltyActiveNotification( $data ) {
         
        $notification = new Notification();
        $notification->user_id = $data['vendor_id'];        
        $notification->subject_id = $data['loyalty_id'];
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.LOYALTY_ACTIVE');         
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.LOYALTY_ACTIVE'), $data['vendor_id'], $data['loyalty_id']);
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    } 

    public static function saveEditProfileNotification( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data['vendor_id'];
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.EDIT_PROFILE');         
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.EDIT_PROFILE'), $data->vendor_id);
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }

    public static function saveEditProfileTagNotification( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data['vendor_id'];
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.EDIT_PROFILE');         
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.EDIT_TAG'), $data->vendor_id);
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }

    public static function saveTransactionNotification( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data->vendor_id;        
        $notification->subject_id = $data->transaction_id;
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.TRANSACTION');         
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.TRANSACTION'), $data->user_id, $data->loyalty_id);
        $notification->amount = $data->user_bill_amount;
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }

    public static function saveMerchantPointNotification( $vendorId, $userId, $usrPoint, $transactionId) {
        
        $notification = new Notification();
        $notification->user_id = $vendorId;        
        $notification->subject_id = $transactionId;
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.TRANSACTION');
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.MERBALANCE'), $vendorId, $usrPoint, $userId);         
        $notification->amount = Config::get('constant.SYMBOL.MINUS').$usrPoint;
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }

    public static function getMerchantNotificationsDetails( $vendorId ){
        
        $data = Notification::where("user_id", '=', $vendorId)->select('id','user_id','subject_id','image','message','amount','created_at')->orderBy('id', 'DESC')->get();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }
    
    

    public static function saveUserLoginNotification( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data['user_id'];
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.LOGIN');        
        $notification->message = Config::get('constant.NOTIFICATION.USER.LOGIN');
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );

    }

    public static function saveUserLogoutNotification( $data ) {
        
        $notification = new Notification();
        $notification->user_id = $data->user_id;        
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.LOGOUT');        
        $notification->message = Config::get('constant.NOTIFICATION.USER.LOGOUT');
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );

    } 

    public static function saveRedeemedNotificationDetail( $data, $redeemCode ) {
        
        $notification = new Notification();
        $notification->user_id = $data['user_id'];        
        $notification->subject_id = $redeemCode;
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.REDEEMED_CODE');         
        $notification->message = Config::get('constant.NOTIFICATION.USER.REDEEMED_CODE');
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }

    public static function saveUserPointNotification( $vendorId, $userId, $usrPoint, $transactionId) {
        
        $notification = new Notification();
        $notification->user_id = $userId;        
        $notification->subject_id = $transactionId;
        $notification->image = url('/')."/images/notification/".Config::get('constant.NOTIFICATION-IMG.TRANSACTION');         
        $notification->message = CustomHelper::outputString( Config::get('constant.NOTIFICATION.MERCHANT.USRBALANCE'), $vendorId, $usrPoint, $userId);
        $notification->amount = Config::get('constant.SYMBOL.PLUS').$usrPoint;
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        return ( isset( $notification->id ) && !empty( $notification->id ) ? $notification->id : '' );
    }


}   
