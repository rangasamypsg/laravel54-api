<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantSocialMedia extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_social_media";
    
    protected $primaryKey = 'id';
    
    /**
    * The table associated with the model timestamp.
    *
    * @var string
    */
    //public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'vendor_id','social_name'
    ];


    public static function getMerchantSocialMediaDetails( $vendorId ) {
        
        $data = MerchantSocialMedia::where(['vendor_id' => $vendorId])->select("id","social_name")->orderBy('id', 'DESC')->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

    public static function getSocialMediaDetails( $vendorId, $name ) {
        
        $data = MerchantSocialMedia::where(['vendor_id' => $vendorId])
        //->where(['name' => $name])
        ->select("id","social_name","name")->orderBy('id', 'DESC')->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }
     

}
