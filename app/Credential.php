<?php

namespace App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "credentials";
    
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
        'mobile_number','user_type','is_mobile_verified'
    ];

  
    public static function checkMobileNoExists($mobileNo) {
        
        $data = Credential::where("mobile_number", '=', $mobileNo)->first();
 
        return $data;
    }


    public static function saveCredentials($data) {
        
        $credential = new Credential();
        $credential->mobile_number =  $data['mobile_number'];
       // $credential->password =  Hash::make($data['password']);
        $credential->user_type =  $data['user_type'];
        $credential->is_mobile_verified = 1;
        $credential->save();

        return ( isset($credential->id) && !empty($credential->id) ? $credential->id : '' );

    }

}
