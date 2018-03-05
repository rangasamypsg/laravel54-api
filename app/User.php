<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "user_details";
    
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
        'full_name', 'email_id', 'mobile_number', 'city', 'address_id', 'is_email_verified'
    ];


    // validate
    public static $regisUserRules = [
        
        'email_id' => 'required|string|email|max:255|unique:user_details',
        'mobile_number' => 'required|unique:credentials',
        
    ];

    public static function saveUsers($data, $address_id){

        $user = new UserDetail();
        $user->user_id = '0';
        $user->full_name = $data['full_name'];
        $user->email_id = $data['email_id'];
        $user->mobile_number = $data['mobile_number'];
        $user->address_id = ( ( isset($address_id) && !empty($address_id) ) ? $address_id : '' );
        $user->is_email_verified = 0;
        $user->location = 0;
        $user->user_level = 1;            
        $user->save();

        return ( isset($user->id) && !empty($user->id) ? $user->id : '' );
    }
    
}
