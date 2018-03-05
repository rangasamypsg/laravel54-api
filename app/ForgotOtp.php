<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgotOtp extends Model
{
     /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "forgot_otp";
    
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
        
        $data = ForgotOtp::where( 'mobile_number', '=', $mobileNumber )->delete();

        return $data;
    }


}
