<?php

namespace App\Helpers;
use App\Helpers\CustomHelper;
use App\MerchantDetail;
use App\Address;
use App\Credential;
use App\MobileOtp;
use App\ForgotOtp;
use App\BusinessRule;
use App\CheckinLimit;
use App\BusinessType;
use App\UserMobileOtp;
use App\Loyalty;
use App\RedeemCode;
use App\Transaction;
use App\LoyaltyBalance;
use App\MerchantImage;
use App\MerchantTags;
use App\TagMerchants;
use App\MerchantFeatureDetail;
use App\MerchantFeatureImage;
use App\MerchantSocialMedia;
use App\UserDetail;
use Config;
use Mail;
use DB;
class CustomHelper
{
    public static function fooBar()
    {
        return 'it works!';
    }

    public static function __getRememberToken() {
        //generate Token Random
        $remember_token = str_random(60); 
        return  ( isset( $remember_token ) && !empty( $remember_token ) ? $remember_token : '');
    }

    public static function checkEmailMobileNoExists($errorsData){
        
        $message = '';
       
        if(isset($errorsData['mobile_number'][0]) && !empty($errorsData['mobile_number'][0])){
            $message = "Mobile number already exist. Please try with another mobile number.";
        }
        if(isset($errorsData['email_id'][0]) && !empty($errorsData['email_id'][0])){          
            $message = "Email ID already exist. Please try with different mail id.";
        }
        if(isset($errorsData['email_id'][0]) && !empty($errorsData['email_id'][0]) AND isset($errorsData['mobile_number'][0]) && !empty($errorsData['mobile_number'][0])){
            $message = "Mobile number and Email ID already exists.";
        }                        
       
        return $message;
    }

    public static function isCheckMobileNoExists($mobileNumber){
         
        $credentialDetail = new Credential();
        $data = $credentialDetail->checkMobileNoExists( $mobileNumber );
        return  ( isset( $data ) && !empty( $data ) ? $data : '');

    }    

    public static function __otpGeneration($mobileNo) {
        //generate Random otp
        $otp = rand(1000, 9999);
        return  ( isset( $otp ) && !empty( $otp ) ? $otp : '');
    }
    
    public static function __codeGeneration($type,$type_code) 
    {

         switch ($type) {
            case Config::get('constant.ZOINUSER.MERCHANT'):
               
                $merchant = MerchantDetail::orderBy('id','Desc')->first();
                 
                if(!empty($merchant['id'])) {
                    $id = $merchant['id'];
                    $incrementVal = (( $id >= Config::get('constant.NUMBER.NINE') ) ? (( $id >= Config::get('constant.NUMBER.NINETYNINE') ) ? ++$id : Config::get('constant.NUMBER.ZERO').++$id ) : Config::get('constant.AUTOINCREMENT.D-ZERO').++$id );
                } else {
                    $incrementVal = Config::get('constant.AUTOINCREMENT.DEFAULT');
                }
                return $type_code."".$incrementVal;
            break;
            case Config::get('constant.ZOINUSER.USER'):
                 
                $user = UserDetail::orderBy('id','Desc')->first();

                if(!empty($user['id'])) {
                    $id = $user['id'];
                    $incrementVal = (( $id >= Config::get('constant.NUMBER.NINE') ) ? (( $id >= Config::get('constant.NUMBER.NINETYNINE') ) ? ++$id : Config::get('constant.NUMBER.ZERO').++$id ) : Config::get('constant.AUTOINCREMENT.D-ZERO').++$id );
                } else {
                    $incrementVal = Config::get('constant.AUTOINCREMENT.DEFAULT');
                }
                return $type_code."".$incrementVal;
            break;
            case Config::get('constant.ZOINUSER.LOYALTY'):
                 
                $loyalty = Loyalty::orderBy('id','Desc')->first();

                if(!empty($loyalty['id'])) {
                    $id = $loyalty['id'];
                    $incrementVal = (( $id >= Config::get('constant.NUMBER.NINE') ) ? (( $id >= Config::get('constant.NUMBER.NINETYNINE') ) ? ++$id : Config::get('constant.NUMBER.ZERO').++$id ) : Config::get('constant.AUTOINCREMENT.D-ZERO').++$id );
                } else {
                    $incrementVal = Config::get('constant.AUTOINCREMENT.DEFAULT');
                }
                return $type_code."".$incrementVal;
            break;
            case Config::get('constant.ZOINUSER.TRANSACTION'):
                 
                $Transaction = Transaction::orderBy('id','Desc')->first();

                if(!empty($Transaction['id'])) {
                    $id = $Transaction['id'];
                    //$incrementVal = (( $id >= 99 ) ? (( $id >= 9 ) ? "0".++$id : ++$id ) : "00".++$id );
                    $incrementVal = (( $id >= Config::get('constant.NUMBER.NINE') ) ? (( $id >= Config::get('constant.NUMBER.NINETYNINE') ) ? ++$id : Config::get('constant.NUMBER.ZERO').++$id ) : Config::get('constant.AUTOINCREMENT.D-ZERO').++$id );
                } else {
                    $incrementVal = Config::get('constant.AUTOINCREMENT.DEFAULT');
                }
                return $type_code."".$incrementVal;
            break;
            case Config::get('constant.ZOINUSER.NOTIFICATION'):
                 
                $Notification = Notification::orderBy('id','Desc')->first();

                if(!empty($Notification['id'])) {
                    $id = $Notification['id'];
                    $incrementVal = (( $id >= Config::get('constant.NUMBER.NINE') ) ? (( $id >= Config::get('constant.NUMBER.NINETYNINE') ) ? ++$id : Config::get('constant.NUMBER.ZERO').++$id ) : Config::get('constant.AUTOINCREMENT.D-ZERO').++$id );
                } else {
                    $incrementVal = Config::get('constant.AUTOINCREMENT.DEFAULT');
                }
                return $type_code."".$incrementVal;
            break;

        }  
    }

    public static function __AutoIncrement( $notificationId ) {
        
        if(!empty( $notificationId )) {
            $id = $notificationId;
            $incrementVal = (( $id > Config::get('constant.NUMBER.NINE') ) ? (( $id > Config::get('constant.NUMBER.NINETYNINE') ) ? $id : Config::get('constant.NUMBER.ZERO').$id ) : Config::get('constant.AUTOINCREMENT.D-ZERO').$id );
        } else {
            $incrementVal = Config::get('constant.AUTOINCREMENT.DEFAULT');
        } 
        return Config::get('constant.FORMAT-CODE.NOTIFICATION_CODE').$incrementVal;
    }

    public static function getTokenEncode($vendorCode) {
        //return hash_hmac('sha256', str_random(40), config('app.key'));
        $key = Config::get('constant.ENCRYPT.key');
        return $encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $vendorCode, MCRYPT_MODE_CBC, md5(md5($key))));
    }

    public static function getTokenDecode($encoded) {
        $key = Config::get('constant.ENCRYPT.key');
        return $decoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encoded), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    }

    public static function sendEmailNotification($userid = null,$emailType){
        
        switch ($emailType) {

            case Config::get('constant.ZOINUSER.MERCHANT'):
            
                $merchant = MerchantDetail::findOrFail( $userid );
                $addressDetail = new Address();

            // $confirmation_code =  self::getTokenEncode($merchant['vendor_id']);
            /* $confirmation_code = str_random(60);
                $merchant->confirmation_code = $confirmation_code;
                $merchant->save(); */
                $address = $addressDetail->getAddressDetails( $merchant['address_id'] );

                $data = [
                    'vendor_id' => $merchant['vendor_id'],
                    'company_name' => $merchant['company_name'],
                    'email_id' => $merchant['email_id'],
                    'contact_person' => $merchant['contact_person'],
                    'mobile_number' => $merchant['mobile_number'],
                    'address' => (($address['address']) ? $address['address'] : ' '),
                    'city' => (($address['city']) ? $address['city'] : ' '),
                   // 'location' => (($merchant['location']) ? $merchant['location'] : '--'),
                    'business_type' => 'Restratunt',
                    'merchant_level' => 'Level01',
                   // 'confirmation_code' => $confirmation_code,
                    //'status' => (( $merchant['status'] == 0) ? 'In Active' : 'Active' ),
                ];
                
                Mail::send('emails.subscribed', $data, function($message) use ($data){
                    
                        $message->from($data['email_id']);
                        $message->to($data['email_id']);
                        $message->subject('Zoin Admin Request');

                });
                
                break;
            case Config::get('constant.ZOINUSER.ADMIN'):
            
                    $merchant = MerchantDetail::findOrFail($userid);
                    $addressDetail = new Address();
                    $address = $addressDetail->getAddressDetails( $merchant['address_id'] );
                    /* echo "<pre>";
                    print_r($merchant);
                    exit; */
                    
                    $data = [
                        'vendor_id' => $merchant['vendor_id'],
                        'company_name' => $merchant['company_name'],
                        'email_id' => $merchant['email_id'],
                        'contact_person' => $merchant['contact_person'],
                        'mobile_number' => $merchant['mobile_number'],
                        'address' => (($address['address']) ? $address['address'] : ' '),
                        'city' => (($address['city']) ? $address['city'] : ' '),
                        'business_type' => 'Restratunt',
                        'merchant_level' => 'Level01',
                        //'status' => (( $merchant['status'] == 0) ? 'In Active' : 'Active' ),
                    ];
                
                    Mail::send('emails.welcome', $data, function($message) use ($data){
                            
                            $message->to(Config::get('settings.Email.admin-email'));
                            $message->subject('Zoin Merchant Request');
                    });

                break;
            case Config::get('constant.ZOINUSER.LOYALTY'):        
            
                $emailLoyaltyDetails = new Loyalty();
                $loyalty = $emailLoyaltyDetails->getMerchantStatusLoyaltyDetails( $userid );
                $getMerDetails = new MerchantDetail();
                $merchant = $getMerDetails->getMerchantDetails( $loyalty['vendor_id'] );
                $data = [
                    'loyalty_id' => $loyalty['loyalty_id'],
                    'max_checkin' => $loyalty['max_checkin'],
                    'max_bill_amount' => $loyalty['max_bill_amount'],
                    'zoin_point' => $loyalty['zoin_point'],
                    'description' => $loyalty['description'],
                    'vendor_id' => $loyalty['vendor_id'],
                    'email_id' => $merchant['email_id'],
                    'contact_person' => $merchant['contact_person'],                     
                ];                 
                Mail::send('emails.loyalty', $data, function($message) use ($data){
                    //$message->from($data['email_id']);
                    $message->to($data['email_id']);
                    $message->subject('Zoin Loyalty Creation');
                });               
                
            break;
                
            case "green":
                echo "Your favorite color is green!";
                break;
            
        }

    }

    public static function getFirstLetterReturn($data) {
        
         $strReturn = substr(ucwords($data),0,1);  
         return  ( isset( $strReturn ) && !empty( $strReturn ) ? $strReturn : '');
     }
 
     public static function getCamelCase($data) {
         
         $strReturn = ucwords($data);
         return  ( isset( $strReturn ) && !empty( $strReturn ) ? $strReturn : '');
     }
 
     public static function isCheckLoyaltyStatus($status) {
         
        switch ($status) {
            
            case Config::get('constant.LOYALTY_STATUS.CREATED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CREATED') ) ? Config::get('constant.LOYALTY_STATUS.UNAPPROVED') : '');
            
            break;
            
            case Config::get('constant.LOYALTY_STATUS.INACTIVE') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.INACTIVE') ) ? Config::get('constant.LOYALTY_STATUS.ACTIVATE') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.OPEN') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.OPEN') ) ? Config::get('constant.LOYALTY_STATUS.ACTIVE') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.CLOSED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CLOSED') ) ? Config::get('constant.NOT_APPROVED') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.DENIED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.DENIED') ) ? Config::get('constant.DELETED') : '');
            
            break;

        }
    }

    public static function getLoyaltyStatusBasedKey($status) {
         
        switch ($status) {
            
            case Config::get('constant.LOYALTY_STATUS.CREATED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CREATED') ) ? Config::get('constant.TEXT.ONE') : '');
            
            break;
            
            case Config::get('constant.LOYALTY_STATUS.INACTIVE') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.INACTIVE') ) ? Config::get('constant.TEXT.TWO') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.OPEN') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.OPEN') ) ? Config::get('constant.TEXT.THREE') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.CLOSED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CLOSED') ) ? Config::get('constant.TEXT.FOUR') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.DENIED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.DENIED') ) ? Config::get('constant.TEXT.FIVE') : '');
            
            break;

        }
    }

    public static function getPopupMenuContent($status = NULL) {
         
        switch ($status) {
            
            case Config::get('constant.LOYALTY_STATUS.CREATED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CREATED') ) ? Config::get('constant.NOTIFICATION.POPUP.INACTIVE') : '');
            
            break;
            
            case Config::get('constant.LOYALTY_STATUS.INACTIVE') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.INACTIVE') ) ? Config::get('constant.NOTIFICATION.POPUP.ACTIVATE') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.OPEN') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.OPEN') ) ? Config::get('constant.NOTIFICATION.POPUP.OPEN') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.CLOSED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CLOSED') ) ? Config::get('constant.NOTIFICATION.POPUP.CLOSED') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.DENIED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.DENIED') ) ? Config::get('constant.NOTIFICATION.POPUP.DENIED') : '');
            
            break;

            default:
             
                return  Config::get('constant.NOTIFICATION.POPUP.ADD_LOYALTY');

        }
    }

    public static function getPopupStatusBasedKey($status) {
         
        switch ($status) {
            
            case Config::get('constant.LOYALTY_STATUS.CREATED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CREATED') ) ? Config::get('constant.TEXT.TWO') : '');
            
            break;
            
            case Config::get('constant.LOYALTY_STATUS.INACTIVE') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.INACTIVE') ) ? Config::get('constant.TEXT.THREE') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.OPEN') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.OPEN') ) ? Config::get('constant.TEXT.FOUR') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.CLOSED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.CLOSED') ) ? Config::get('constant.TEXT.FIVE') : '');
            
            break;

            case Config::get('constant.LOYALTY_STATUS.DENIED') :
            
                return  ( isset( $status ) &&  ( $status == Config::get('constant.LOYALTY_STATUS.DENIED') ) ? Config::get('constant.TEXT.SIX') : '');
            
            break;

        }
        
    }
    
    public static function sendSms( $mobileNumber, $otp ) {

        //Your authentication key
        $authKey = Config::get('settings.SMS.AUTHENTICATION_KEY');

        //Multiple mobiles numbers separated by comma
        $mobileNumber = Config::get('settings.SMS.COUNTRY_CODE').$mobileNumber;

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = Config::get('settings.SMS.SENDER_ID');

        //Your message to send, Add URL encoding here.
        $message = urlencode("Thanks for becoming a zoin member. Your OTP is: $otp");

        //Define route 
        $route = Config::get('settings.SMS.ROUTE');

        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url="http://sms.servercake.in/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);        

        return  ( isset( $output ) && !empty( $output ) ? $output : '');

    } // SendSms

    public static function sendSmsService( $mobileNumber, $data ) {

        //Your authentication key
        $authKey = Config::get('settings.SMS.AUTHENTICATION_KEY');

        //Multiple mobiles numbers separated by comma
        $mobileNumber = Config::get('settings.SMS.COUNTRY_CODE').$mobileNumber;

        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = Config::get('settings.SMS.SENDER_ID');

        //Your message to send, Add URL encoding here.
        $message = urlencode("$data");

        //Define route 
        $route = Config::get('settings.SMS.ROUTE');

        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url="http://sms.servercake.in/api/sendhttp.php";

        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ));

        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        //get response
        $output = curl_exec($ch);

        //Print error if any
        if(curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);        

        return  ( isset( $output ) && !empty( $output ) ? $output : '');

    } // SendSms

    
    public static function isCheckLoginActivateUser( $mobileNumber ) {

        DB::table('credentials')->where('mobile_number', $mobileNumber)->update( ['is_mobile_verified' => 1] );

        DB::table('mobile_otp')->where('mobile_number', $mobileNumber)->delete();

    }

    public static function isCheckMerchantLoginActivateUser( $mobileNumber ) {
        
        DB::table('merchant_details')->where('mobile_number', $mobileNumber)->update( ['is_login_approved' => 1] );

        DB::table('mobile_otp')->where('mobile_number', $mobileNumber)->delete();

    }

    public static function isExistOTPRemove( $mobileNumber, $otpGenerate ) {

       $existOTP = DB::table('mobile_otp')->where('mobile_number', $mobileNumber)->get();
        
       if( ! $existOTP->isEmpty() ) {
            DB::table('mobile_otp')->where('mobile_number', $mobileNumber)->delete();
       }  
                
       $merchantOtp = new MobileOtp();
       $merchantOtp->mobile_number = $mobileNumber;
       $merchantOtp->otp = $otpGenerate;
       $merchantOtp->save();

       return  ( isset( $merchantOtp->id ) && !empty( $merchantOtp->id ) ? $merchantOtp->id : '');
    }

    public static function isExistForgotOTPRemove( $mobileNumber, $otpGenerate ) {
        
        $existOTP = DB::table('forgot_otp')->where('mobile_number', $mobileNumber)->get();
        
        if( ! $existOTP->isEmpty() ) {
            DB::table('forgot_otp')->where('mobile_number', $mobileNumber)->delete();
        }  
                
        $forgotOtp = new ForgotOtp();
        $forgotOtp->mobile_number = $mobileNumber;
        $forgotOtp->otp = $otpGenerate;
        $forgotOtp->save();

        return  ( isset( $forgotOtp->id ) && !empty( $forgotOtp->id ) ? $forgotOtp->id : '');
    }
  

    public static function isUserExistOTPRemove( $mobileNumber, $otpGenerate ) {
        
        $existOTP = DB::table('user_mobile_otp')->where('mobile_number', $mobileNumber)->get();
        
        if( ! $existOTP->isEmpty() ) {
            DB::table('user_mobile_otp')->where('mobile_number', $mobileNumber)->delete();
        }  
                
        $userOtp = new UserMobileOtp();
        $userOtp->mobile_number = $mobileNumber;
        $userOtp->otp = $otpGenerate;
        //$userOtp->status = Config::get('constant.NUMBER.ZERO');
        $userOtp->save();

        return  ( isset( $userOtp->id ) && !empty( $userOtp->id ) ? $userOtp->id : '');
    }

    public static function isCheckUserLoginActivateUser( $mobileNumber ) {
        
        DB::table('user_details')->where('mobile_number', $mobileNumber)->update( ['is_mobile_verified' => 1] );

        DB::table('user_mobile_otp')->where('mobile_number', $mobileNumber)->delete();

    }
    
    public static function activateUserStatus( $mobileNumber ) {
        
        DB::table('user_details')->where('mobile_number', $mobileNumber)->update( ['is_login_approved' => 1] );

        DB::table('user_mobile_otp')->where('mobile_number', $mobileNumber)->delete();

    }

    public static function getZoinDateFormat( $create_date ) {
        
        $timestamp = strtotime($create_date);
         
        return date('l, j M, Y', $timestamp);
    
    }

    public static function getZoinTimeFormat( $create_date ) {
            
        return date('h:ia',strtotime(Config::get('settings.Date_Format'),strtotime($create_date)));
    
    }

    public static function getZoinDateandTimeFormat( $create_date ) {
            
        return date('d M Y, h:i a',strtotime(Config::get('settings.Date_Format'),strtotime($create_date)));
    
    }

    public static function __redeemCodeGeneration( $lengths, $length ) {
       
        $str = $strs = "";
        //$strs = "";
        $characters = array_merge(range('A','Z'));
        $maxs = count($characters) - 1;
        for ($j = 0; $j < $lengths; $j++) {
            $rands = mt_rand(0, $maxs);
            $strs .= $characters[$rands];
        }
        $character = array_merge( range('0','9') );
        $max = count($character) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $character[$rand];
        }
        return $strs.$str;
        
    }
    
    public static function getPromotionByRedeemCode( $redeemCode, $mobileNumber ) {
        
        $redeemCodeExist = RedeemCode::where(['redeem_code' => $redeemCode])->where(['mobile_number' => $mobileNumber])->first();
        
        /* echo "<pre>";
        print_r($redeemCodeExist);
        exit; */
         
        return  ( isset( $redeemCodeExist ) && !empty( $redeemCodeExist ) ? $redeemCodeExist : '');
    }

    public static function __getRedeemCodeGeneration() {
      
        //generate Random otp
        $otp = rand(1000, 9999);

        return  ( isset( $otp ) && !empty( $otp ) ? $otp : '');
    }
    
    public static function getUserTransactionsProcess( $redeemCode ) {
       
        $data = array();
        $usrTransAmt = $usrBalance = 0;
        $getUserDetail = new RedeemCode();
        $userDetails = $getUserDetail->getUserTransactions( $redeemCode );

        //echo "<pre>";
        //print_r($userDetails); exit;

        $getUserTransactions = new Transaction();
        $usrTransAmt = $getUserTransactions->getUserTransactions( $userDetails );
        $usrBalance = ( isset( $userDetails[0]->user_balance ) && !empty( $userDetails[0]->user_balance ) ? $userDetails[0]->user_balance : 0);
        if( isset( $usrBalance ) && !empty( $usrBalance ) ) {
            $usrTransAmt = $usrTransAmt + $usrBalance; 
        }
        $total = Config::get('constant.NUMBER.ONE');
        $data['mobile_number'] = $userDetails[0]->mobile_number;
        $data['fullname'] = $userDetails[0]->full_name;
        $data['user_level'] = "Level ". ( isset( $userDetails[0]->user_level ) && !empty( $userDetails[0]->user_level ) ? $userDetails[0]->user_level : 0);
        $data['max_checkin'] = ( isset( $userDetails[0]->max_checkin ) && !empty( $userDetails[0]->max_checkin ) ? $userDetails[0]->max_checkin : 0);
        $data['max_bill_amount'] = ( isset( $userDetails[0]->max_bill_amount ) && !empty( $userDetails[0]->max_bill_amount ) ? $userDetails[0]->max_bill_amount : 0);
        $data['zoin_point'] = ( isset( $userDetails[0]->zoin_point ) && !empty( $userDetails[0]->zoin_point ) ? $userDetails[0]->zoin_point : 0);
        $data['user_checkin'] = ( isset( $userDetails[0]->user_checkin ) && !empty( $userDetails[0]->user_checkin ) ? $total += $userDetails[0]->user_checkin : 1);
        $data['user_bill_amount'] = ( isset( $usrTransAmt ) && !empty( $usrTransAmt ) ? $usrTransAmt : 0);
        $data['vendor_id'] = ( isset( $userDetails[0]->vendor_id ) && !empty( $userDetails[0]->vendor_id ) ? $userDetails[0]->vendor_id : 0);
        $data['user_id'] = ( isset( $userDetails[0]->user_id ) && !empty( $userDetails[0]->user_id ) ? $userDetails[0]->user_id : 0);
        $data['loyalty_id'] = ( isset( $userDetails[0]->loyalty_id ) && !empty( $userDetails[0]->loyalty_id ) ? $userDetails[0]->loyalty_id : 0);
        $data['redeem_code'] = ( isset( $userDetails[0]->redeem_code ) && !empty( $userDetails[0]->redeem_code ) ? $userDetails[0]->redeem_code : 0);
 
        return  ( isset( $data ) && !empty( $data ) ? $data : '');

    }


    public static function getUserAllTransactionsProcess( $userDetails ) {
        
        $getUserTransactions = new Transaction();
        $data = $getUserTransactions->getUserAllTransactions( $userDetails );
        return  ( isset( $data ) && !empty( $data ) ? $data : 0);

    }
    
    public static function getUserRedeemedCheckIns( $userDetails ) {
        
        $redeemProcess = new RedeemCode();
        $data = $redeemProcess->userRedeemedCodeCount( $userDetails );
        return  ( isset( $data['user_checkin'] ) && !empty( $data['user_checkin'] ) ? $data['user_checkin'] : 0);

    }

    public static function get_currency_symbol($cc = 'USD') {

        $cc = strtoupper($cc);
        $currency = array(
        "USD" => "&#36;" , //U.S. Dollar
        "AUD" => "&#36;" , //Australian Dollar
        "BRL" => "R&#36;" , //Brazilian Real
        "CAD" => "C&#36;" , //Canadian Dollar
        "CZK" => "K&#269;" , //Czech Koruna
        "DKK" => "kr" , //Danish Krone
        "EUR" => "&euro;" , //Euro
        "HKD" => "&#36" , //Hong Kong Dollar
        "HUF" => "Ft" , //Hungarian Forint
        "ILS" => "&#x20aa;" , //Israeli New Sheqel
        "INR" => "&#8377;", //Indian Rupee
        "JPY" => "&yen;" , //Japanese Yen 
        "MYR" => "RM" , //Malaysian Ringgit 
        "MXN" => "&#36" , //Mexican Peso
        "NOK" => "kr" , //Norwegian Krone
        "NZD" => "&#36" , //New Zealand Dollar
        "PHP" => "&#x20b1;" , //Philippine Peso
        "PLN" => "&#122;&#322;" ,//Polish Zloty
        "GBP" => "&pound;" , //Pound Sterling
        "SEK" => "kr" , //Swedish Krona
        "CHF" => "Fr" , //Swiss Franc
        "TWD" => "&#36;" , //Taiwan New Dollar 
        "THB" => "&#3647;" , //Thai Baht
        "TRY" => "&#8378;" //Turkish Lira
        );
        
        if(array_key_exists($cc, $currency)){
            return $currency[$cc];
        }
    }

    public static function getUserdetails($userId) {
        
        $records = UserDetail::where("user_id", '=', $userId)->first();
        
        return ( isset( $records['full_name'] ) && !empty( $records['full_name'] ) ? $records['full_name'] : '' ); 
    }
    
    public static function getMerchantStatus( $mobileNumber ) {
        
        $records = DB::table('merchant_details as m')
                    ->select("ms.status_name","ms.id")
                    ->join('merchant_status as ms', 'm.is_admin_approved', '=', 'ms.id')
                    ->where('m.mobile_number', '=', $mobileNumber )
                    ->orderBy('m.id', 'DESC')
                    ->first();
        //echo "<pre>";
        //print_r($records); exit;

        return  ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getMerchantTransactions( $data ) {
         
        $records = Transaction::where(['vendor_id' => $data->vendor_id])->where(['loyalty_id' => $data->loyalty_id])->where(['transaction_status' => Config::get('constant.LOYALTY_STATUS.APPROVED')])->sum('user_bill_amount');
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }
    
   /* public static function baseEncode64(){
      
        $destinationPath = public_path('/images//');
        $image_parts = explode(";base64,", $input['image']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $destinationPath."".time().'.png';
        file_put_contents($file, $image_base64);
    
    } */

    public static function baseEncode64Image( $base64String ){

        if( !empty( $base64String ) )  {
            $imageContent = Config::get('constant.BASE_ENCODE').$base64String;          
            $destinationPath = public_path('/images//');
            $image_parts = explode(";base64,", $imageContent);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = Config::get('constant.TIMESTAMP').'.'.Config::get('constant.EXTENSION.PNG');
            $destFileName = Config::get('constant.TIMESTAMP').'.'.Config::get('constant.EXTENSION.BMP');
            //$fullPath = Config::get('constant.BASE_IMG_URL')."/images/".$destFileName;
            $fullPath = url('/')."/images/".$destFileName;
            $file = $destinationPath."".$fileName;
            $sourcefile = $destinationPath."".$destFileName;
            file_put_contents($file, $image_base64); 
            CustomHelper::compressImage($file, $sourcefile, Config::get('constant.SIZE.QUALITY')); 
        } 

        return  ( isset( $fullPath ) && !empty( $fullPath ) ? $fullPath : '' );
    }
    
    public static function compressImage($source, $destination, $quality) {

        $info = getimagesize($source);
        
        if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);
    
        elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);
    
        elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);
    
        imagejpeg($image, $destination, $quality);
        @unlink($source);
        return $destination;
    }
    
    public static function merchantFeatureDetails( $vendorId ) {
        
        $MerchantFeatureDetail = new MerchantFeatureDetail();
        $featureDetails = $MerchantFeatureDetail->getMerchantFeatureDetails( $vendorId );

        //echo "<pre>";
        //print_r($featureDetails); exit;
        if( ! $featureDetails->isEmpty() ) {    
            $result = array();
            foreach($featureDetails as $key => $featureDetail ) {                 
               $data = array();
               $data['feature_name'] = ( isset( $featureDetail->feature_name ) && !empty( $featureDetail->feature_name ) ? $featureDetail->feature_name : Config::get('constant.EMPTY') );
               $data['feature_image'] = ( isset( $featureDetail->feature_image ) && !empty( $featureDetail->feature_image ) ? $featureDetail->feature_image : Config::get('constant.EMPTY') );
               array_push($result,$data); 
            }
        }  else {
            $records = array();
            for($i = 0; $i <= 3; $i++ ){
               $data = array();
               $data['feature_name'] = "No Image";
               $data['feature_image'] = asset('/assets/images/zoin_empty.png');
               array_push($records,$data);
            }
        }
        
        return  ( isset( $result ) && !empty( $result ) ? $result : $records );
    }

    public static function merchantLoyaltyDetails( $vendorId ) {
        
        $loyaltyDetail = new Loyalty();
        $loyaltyDetails = $loyaltyDetail->getLoyaltyOpenDetails( $vendorId ); 
      
        if( ! $loyaltyDetails->isEmpty() ) {
            $loyalty = array();
            $i = Config::get('constant.NUMBER.ZERO');
            foreach($loyaltyDetails as $key => $loyaltyDetail ) {
               
                $loyalty[$i]['loyalty_id'] = ( isset( $loyaltyDetail['loyalty_id'] ) && !empty( $loyaltyDetail['loyalty_id'] ) ? $loyaltyDetail['loyalty_id'] : Config::get('constant.EMPTY') );
                //$loyalty[$i]['offer_type'] = ( isset( $loyaltyDetail['offer_type'] ) && !empty( $loyaltyDetail['offer_type'] ) ? $loyaltyDetail['offer_type'] : Config::get('constant.EMPTY') );
                $loyalty[$i]['max_checkin'] = ( isset( $loyaltyDetail['max_checkin'] ) && !empty( $loyaltyDetail['max_checkin'] ) ? $loyaltyDetail['max_checkin'] : Config::get('constant.EMPTY') );
                $loyalty[$i]['max_bill_amount'] = ( isset( $loyaltyDetail['max_bill_amount'] ) && !empty( $loyaltyDetail['max_bill_amount'] ) ? $loyaltyDetail['max_bill_amount'] : Config::get('constant.EMPTY') );
                $loyalty[$i]['zoin_point'] = ( isset( $loyaltyDetail['zoin_point'] ) && !empty( $loyaltyDetail['zoin_point'] ) ? $loyaltyDetail['zoin_point'] : Config::get('constant.EMPTY') );
                $loyalty[$i]['loyalty_status'] = ( isset( $loyaltyDetail['loyalty_status'] ) && !empty( $loyaltyDetail['loyalty_status'] ) ? $loyaltyDetail['loyalty_status'] : Config::get('constant.EMPTY') );
                $loyalty[$i]['description'] = ( isset( $loyaltyDetail['description'] ) && !empty( $loyaltyDetail['description'] ) ? $loyaltyDetail['description'] : Config::get('constant.EMPTY') );
                $loyalty[$i]['date_format'] = CustomHelper::getZoinDateandTimeFormat( $loyaltyDetail['created_at'] );
                $i++;
            }
        }
        return  ( isset( $loyalty ) && !empty( $loyalty ) ? $loyalty : Config::get('constant.NORECORDS') ); 
    }   

    public static function merchantProfilePhotoDetails( $vendorId ) {
        
        $getMerchantProfileImage = new MerchantImage();
        $profileImages = $getMerchantProfileImage->getMerchantProfileImageDetails( $vendorId );
      
        if( ! $profileImages->isEmpty() ) {
            $profile = array();
            foreach($profileImages as $key => $profileImage ) {
                $data = array();
                $data['profile_image'] = ( isset( $profileImage['profile_image'] ) && !empty( $profileImage['profile_image'] ) ? $profileImage['profile_image'] : Config::get('constant.EMPTY') );
                array_push($profile,$data);
            }
        }  else {
            $records = array();
            for($i = 0; $i <= 3; $i++ ){    
               $data = array();
               $data['profile_image'] = asset('/assets/images/zoin_empty.png');
               array_push($records,$data);
            }
        }
        return  ( isset( $profile ) && !empty( $profile ) ? $profile : $records ); 
    }  
    
    public static function merchantSocialMediaDetails( $vendorId ) {
        
        $getMerchantSocialMedia = new MerchantSocialMedia();
        $socialMediaNames = $getMerchantSocialMedia->getMerchantSocialMediaDetails( $vendorId );
         
        if( ! $socialMediaNames->isEmpty() ) {
            $socialNames = array();
            foreach($socialMediaNames as $key => $socialMediaName ) {
                $data = array();
                $data['social_name'] = ( isset( $socialMediaName->social_name ) && !empty( $socialMediaName->social_name ) ? $socialMediaName->social_name : Config::get('constant.EMPTY') );
                array_push($socialNames,$data); 
            }
        }
                
        return  ( isset( $socialNames ) && !empty( $socialNames ) ? $socialNames : Config::get('constant.NORECORDS') ); 
    }


    public static function checkMerchantTags( $tagName , $vendorId ) {
        
        $merchantTag = new MerchantTags();
        $getMerTagDetails = $merchantTag->getMerchantTags( $tagName );
         
        if( isset( $getMerTagDetails->tag_name ) && !empty( $getMerTagDetails->tag_name ) ) {
            $saveTag = new TagMerchants();
            $saveTag->saveTagMerchants( $vendorId, $getMerTagDetails->id );
        } else {
            $saveMerTag = new MerchantTags();
            $tagId = $saveMerTag->saveMerchantTag( $tagName );
            $saveTag = new TagMerchants();
            $saveTag->saveTagMerchants( $vendorId, $tagId );           
        }

    }

    public static function merchantTagDetails( $vendorId ) {
         
         $getMerchantTags = new MerchantTags();
         $tags = $getMerchantTags->getMerchantTagLists( $vendorId );
          
         if( ! $tags->isEmpty() ) {
             $tagResponse = array();             
             foreach($tags as $key => $tag ) {
                $data = array();
                $data['tag_id'] = ( isset( $tag->id ) && !empty( $tag->id ) ? $tag->id : Config::get('constant.EMPTY') );
                $data['tag_name'] = ( isset( $tag->tag_name ) && !empty( $tag->tag_name ) ? $tag->tag_name : Config::get('constant.EMPTY') );
                array_push($tagResponse,$data);
             }
         }         
         return  ( isset( $tagResponse ) && !empty( $tagResponse ) ? $tagResponse : Config::get('constant.NORECORDS') ); 
     }

    public static function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        $url = explode("/",$_SERVER["REQUEST_URI"]);
        if ($_SERVER["SERVER_PORT"] != "80") {
         $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]."/".$url[1]."/".$url[2]."/";
        } else {
         $pageURL .= $_SERVER["SERVER_NAME"]."/".$url[1]."/".$url[2]."/";
        }
        return $pageURL;
    }
   

   public static function isCheckMerchantStatus($statusCode) 
   {
        switch ($statusCode) {
            case Config::get('constant.NUMBER.ZERO'):
                return Config::get('constant.MERCHANT_STATUS.UNAPPROVED');
            break;
            case Config::get('constant.NUMBER.ONE'):
                return Config::get('constant.MERCHANT_STATUS.APPROVED');
            break;
            case Config::get('constant.NUMBER.TWO'):
                return Config::get('constant.MERCHANT_STATUS.PENDING');
            break;
            case Config::get('constant.NUMBER.THREE'):
                return Config::get('constant.MERCHANT_STATUS.BLOCKED');
            break;            
       }
   }

   public static function getNotificationKeyStatus($urlString) 
   {
       $url = explode("/", trim($urlString,"/"));
       $status = ( isset( $url ) && !empty( $url ) ? end($url) : Config::get('constant.NUMBER.ZERO') ); 
       
       switch ( $status ) {
            case Config::get('constant.NOTIFICATION-IMG.LOGIN'):
                return Config::get('constant.NUMBER.ONE');
            break;
            case Config::get('constant.NOTIFICATION-IMG.LOGOUT'):
                return Config::get('constant.NUMBER.TWO');
            break;
            case Config::get('constant.NOTIFICATION-IMG.LOYALTY_SUBMIT'):
                return Config::get('constant.NUMBER.THREE');
            break;
            case Config::get('constant.NOTIFICATION-IMG.EDIT_PROFILE'):
                return Config::get('constant.NUMBER.FOUR');
            break;
            case Config::get('constant.NOTIFICATION-IMG.REDEEMED_CODE'):
                return Config::get('constant.NUMBER.FIVE');
            break;
            case Config::get('constant.NOTIFICATION-IMG.TRANSACTION'):
                return Config::get('constant.NUMBER.SIX');
            break;
            default:
                return Config::get('constant.NUMBER.ZERO');
            break;
      }
   }

   public static function merchantLoyaltyBalance($maxAmount , $zoinPoint) 
   {   
       $pendingBal = Config::get('constant.NUMBER.ZERO');
       if( ( !empty( $maxAmount ) && !empty( $zoinPoint ) ) ) {
             
            if( $maxAmount >= $zoinPoint) {           
                $pendingBal = $maxAmount / $zoinPoint;
            }

        }       
       return  ( isset( $pendingBal ) && !empty( $pendingBal ) ? (int) $pendingBal : Config::get('constant.NUMBER.ZERO') ); 
   }
   
   
   public static function outputString($textMsg, $vendorId , $userId = NULL, $data = Null ){
        
        $txt = sprintf(__($textMsg), $vendorId, $userId, $data);
        return $txt;
   }


    
}