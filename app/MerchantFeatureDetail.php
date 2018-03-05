<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class MerchantFeatureDetail extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_feature_details";
        
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
        'vendor_id', 'feature_id'
    ];
 
    public static function getMerchantFeatureDetails( $vendorId ){
        
        $data = DB::table('merchant_feature_details as mf')
            ->select("mf.id","mf.feature_id","mf.vendor_id","mi.feature_type","mi.feature_name","mi.feature_image")
            ->join('merchant_feature_images as mi', 'mf.feature_id', '=', 'mi.id')
            ->where("mf.vendor_id", '=', $vendorId)
            ->orderBy('mf.id', 'ASC')
            ->get();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }
	 
}
