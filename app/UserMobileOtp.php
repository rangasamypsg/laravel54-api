<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMobileOtp extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "user_mobile_otp";
    
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
         'mobile_number','otp','status'
    ];
 
 
     public function ischeckOtpExistsRemove( $mobileNumber ) {
         
        $data = UserMobileOtp::where( 'mobile_number', '=', $mobileNumber )->delete(); 
        return $data;
     }
 
     public function isCheckActivateUser( $mobileNumber , $otp ) {
         $data =  UserMobileOtp::where( [ 'mobile_number' => $mobileNumber ] )->where( [ 'otp' => $otp ] )->update( [ 'status' => 1 ] );
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
     }
 
     public function isCheckMobileNoExistsOTP( $mobileNumber , $otp ) {
         
         $data = UserMobileOtp::where( 'mobile_number', '=', $mobileNumber )->where( 'otp', '=', $otp )->first();
 
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
     }
}
