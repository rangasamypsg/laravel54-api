<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobileOtp extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "mobile_otp";
   
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
        'mobile_number','otp'
        //,'address','city','post_code'
    ];


    public function ischeckOtpExistsRemove( $mobileNumber ) {
        
        //$data = DB::table('mobile_otp')->where('mobile_number', '=', $mobileNumber )->delete();
        $data = MobileOtp::where( 'mobile_number', '=', $mobileNumber )->delete();

        return $data;
    }

    public function isCheckActivateUser( $mobileNumber , $otp ) {
        $data =  MobileOtp::where( [ 'mobile_number' => $mobileNumber ] )->where( [ 'otp' => $otp ] )->update( [ 'status' => 1 ] );
        return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }

    public function isCheckMobileNoExistsOTP( $mobileNumber , $otp ) {
        
        $data = MobileOtp::where( 'mobile_number', '=', $mobileNumber )->where( 'otp', '=', $otp )->first();

        return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }


}
