<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class MerchantFeatureImage extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_feature_images";
        
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
        'feature_type','feature_name','feature_image'
    ];

    public static function getMerchantFeatureImageDetails( $itemId ){
        
        $data = MerchantFeatureImage::where("id", '=', $itemId)
                    ->select('id','feature_type','feature_name','feature_image')
                    ->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

}
