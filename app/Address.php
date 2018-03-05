<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "address";
    
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
        'address_id'
        //,'address','city','post_code'
    ];

    public static function saveAddress($data) {
        
        $address = new Address();
        $address->address =  $data['address'];
        $address->city = $data['city'];
        //$address->postcode = 0;
        $address->save();

        return ( isset($address->id) && !empty($address->id) ? $address->id : '' );

    }

    public static function saveUserAddress($data) {
        
        $address = new Address();
        $address->address =  $data['address'];
        $address->city = $data['city'];
        $address->save();

        return ( isset($address->id) && !empty($address->id) ? $address->id : '' );

    }

    public static function getAddressDetails($data){
        
        $data = Address::where("address_id", '=', $data)->select('address_id','address','city')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );     
    }

    public static function updateAddress($data,$addressId) {
        //echo $addressId; exit;
        $address = Address::where("address_id", '=', $addressId)->select('address_id','address','city')->first();
        $updateAddress =  Address::where( [ 'address_id' => $addressId ] )->update( [ 'address' => $data['address'], 'city' => $data['city'] ] );
     
        return ( isset($updateAddress) && !empty($updateAddress) ? $updateAddress : '' );  
    }

}
