<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class MerchantBankDetail extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_bank_details";
        
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
        'id','vendor_id','gst_number','author_number','pan_number','ifsc_code','account_number','account_name','bank_name','bank_address','account_type'
    ];
 

    public static function saveMerchantBankDetails( $data, $vendorId ){

        /* $merchantExists = MerchantBankDetail::where("vendor_id", '=', $vendorId)->select('id','vendor_id')->first();
        echo "<pre>";
        print_r($merchantExists);
        exit; */

        $merchant = new MerchantBankDetail();
        $merchant->vendor_id = $vendorId;
        $merchant->gst_number = $data['gst_number'];
        $merchant->author_number = $data['author_number'];
        $merchant->pan_number = $data['pan_number'];
        $merchant->ifsc_code = $data['ifsc_code'];
        $merchant->account_number = $data['account_number'];
        $merchant->account_name = $data['account_name'];
		$merchant->bank_name = $data['bank_name'];
		$merchant->bank_address = $data['bank_address'];
		$merchant->account_type = $data['account_type'];		
        $merchant->save();

        return ( isset($merchant->id) && !empty($merchant->id) ? $merchant->id : '' );
    }
   
    public static function getMerchantBankDetails($vendorId){
        
        $data = MerchantBankDetail::where("vendor_id", '=', $vendorId)->select('id','vendor_id')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );     
    }
    
    public static function updateMerchantBankDetails( $data, $vendorId ){

        $merchant = MerchantBankDetail::where("vendor_id", '=', $vendorId)->first();
        $merchant->gst_number = $data['gst_number'];
        $merchant->author_number = $data['author_number'];
        $merchant->pan_number = $data['pan_number'];
        $merchant->ifsc_code = $data['ifsc_code'];
        $merchant->account_number = $data['account_number'];
        $merchant->account_name = $data['account_name'];
		$merchant->bank_name = $data['bank_name'];
		$merchant->bank_address = $data['bank_address'];
		$merchant->account_type = $data['account_type'];		
        $merchant->save();

        return ( isset($merchant->id) && !empty($merchant->id) ? $merchant->id : '' );
    }

}
