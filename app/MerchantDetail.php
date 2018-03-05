<?php

namespace App;
use DB;
use Config;
use App\Helpers\CustomHelper;
use Illuminate\Database\Eloquent\Model;

class MerchantDetail extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_details";
        
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
        'vendor_id','company_name','email_id','contact_person','mobile_number','address_id','is_email_verified','business_type','merchant_level','is_admin_approved','is_login_approved'
    ];

    // validate
    // read more on validation at http://laravel.com/docs/validation
    public static $regisMerchantRules = [
     
        'email_id' => 'required|string|email|max:255|unique:merchant_details',
        'mobile_number' => 'required|unique:credentials',
        
    ];

    public static function saveMerchants($data, $address_id){

        $merchant = new MerchantDetail();
        $merchant->vendor_id = CustomHelper::__codeGeneration(Config::get('constant.ZOINUSER.MERCHANT'),Config::get('constant.FORMAT-CODE.MERCHANT_CODE'));
        $merchant->company_name = $data['company_name'];
        $merchant->email_id = $data['email_id'];
        $merchant->contact_person = $data['contact_person'];
        $merchant->mobile_number = $data['mobile_number'];
        $merchant->address_id = ( ( isset($address_id) && !empty($address_id) ) ? $address_id : '' );
        $merchant->is_email_verified = Config::get('constant.NUMBER.ZERO');
        $merchant->business_type = Config::get('constant.NUMBER.ONE');
        $merchant->location = Config::get('constant.NUMBER.ZERO');
        $merchant->merchant_level = Config::get('constant.NUMBER.ONE');            
        $merchant->is_admin_approved = Config::get('constant.NUMBER.THREE');            
        $merchant->save();

        return ( isset($merchant->id) && !empty($merchant->id) ? $merchant->id : '' );
    }
   

    public static function getSidebarProfileDetails( $data ){
  
        $sideBarList = DB::table('merchant_details as m')
                    ->select("m.id as merchantId","m.company_name","m.vendor_id","m.is_admin_approved","b.business_type")
                    ->join('business_types as b', 'm.business_type', '=', 'b.id')
                    ->where('m.mobile_number', '=', $data['mobile_number'])
                    ->orderBy('m.id', 'DESC')
                    ->first();

        return ( isset($sideBarList) && !empty($sideBarList) ? $sideBarList : '' );          
    }

    public static function ischeckMobileverified( $mobileNo ){
        
        $data = MerchantDetail::where("mobile_number", '=', $mobileNo)->select('id','vendor_id','company_name','email_id','contact_person','mobile_number','is_admin_approved','is_login_approved')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

    public static function getProfileDetails( $data ){
        
        $sideBarList = DB::table('merchant_details as m')
                    ->select("m.id as merchantId","m.company_name","m.vendor_id","m.address_id","b.business_type","zb.zoin_balance","a.city")
                    ->join('business_types as b', 'm.business_type', '=', 'b.id')
                    ->leftjoin('zoin_balance as zb', 'm.vendor_id', '=', 'zb.vendor_or_user_id')
                    ->join('address as a', 'm.address_id', '=', 'a.address_id')
                    ->where('m.mobile_number', '=', $data['mobile_number'])
                    ->orderBy('m.id', 'DESC')
                    ->first();

        return ( isset($sideBarList) && !empty($sideBarList) ? $sideBarList : '' );          
    }

    public static function getMobileNoBasedVendorId( $mobileNo ){
        
        $data = MerchantDetail::where("mobile_number", '=', $mobileNo)->select('id','vendor_id','company_name')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

    public static function getVendorDetails( $vendorCode ){
        
        $data = MerchantDetail::where("confirmation_code", '=', $vendorCode)->select('id',"company_name","vendor_id","is_email_verified")->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

    public static function updateMerchants( $data ){
        
        $merchants = MerchantDetail::where("mobile_number", '=', $data['mobile_number'])->select('id','vendor_id','address_id')->first();
        $merchants->company_name = $data['company_name'];
        $merchants->email_id = $data['email_id'];
        $merchants->contact_person = $data['contact_person'];
        $merchants->save();

        return ( isset($merchants['address_id']) && !empty($merchants['address_id']) ? $merchants['address_id'] : '' );          
    }

    public static function getEditProfileDetails( $data ){
        
        $editProfileList = DB::table('merchant_details as m')
                    ->select("m.company_name","m.vendor_id","m.email_id","m.contact_person","m.mobile_number","m.latitude","m.longitude","m.description","m.website","m.start_time","m.end_time","m.closed","a.address","a.city",
                    'mb.gst_number','mb.author_number','mb.pan_number','mb.ifsc_code','mb.account_number','mb.account_name','mb.bank_name','mb.bank_address','mb.account_type')
                    ->join('address as a', 'm.address_id', '=', 'a.address_id')
                    ->leftJoin('merchant_bank_details as mb', 'm.vendor_id', '=', 'mb.vendor_id')
                    ->where('m.mobile_number', '=', $data['mobile_number'])
                    ->orderBy('m.id', 'DESC')
                    ->get();

        return ( isset($editProfileList) && !empty($editProfileList) ? $editProfileList : '' ); 
    
    }

    public static function getMerchantDetails( $vendorId ){
        
        $data = MerchantDetail::where("vendor_id", '=', $vendorId)->select('id','vendor_id','company_name','email_id','contact_person','mobile_number','is_admin_approved','is_login_approved')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

    public static function updateDescription( $data ){
        
        $merchants = MerchantDetail::where("mobile_number", '=', $data['mobile_number'])->select('id','vendor_id','description')->first();
        $merchants->description = $data['description'];
        $merchants->save();

        return ( isset($merchants->id) && !empty($merchants->id) ? $merchants->id : '' );          
    }


}
