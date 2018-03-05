<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantImage extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_images";
    
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
        'vendor_id','profile_image'
    ];


    public static function getMerchantProfileImageDetails( $vendorId ) {
        
        $data = MerchantImage::where(['vendor_id' => $vendorId])->select("profile_image")->orderBy('id', 'DESC')->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

    public static function getProfileImageDetails( $vendorId ) {
        
        $data = MerchantImage::where(['vendor_id' => $vendorId])->select("profile_image")->orderBy('id', 'DESC')->first();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

}
