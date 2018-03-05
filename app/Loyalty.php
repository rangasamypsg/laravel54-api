<?php

namespace App;
use Config;
use DB;
use App\Helpers\CustomHelper;
use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "loyalty";
    
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
        'loyalty_status','max_checkin','max_bill_amount','zoin_point','description'
    ];
    
    public static function saveNewloyaltyDetail( $data ) {
        
        $loyalty = new Loyalty();        
        $loyalty->loyalty_id = CustomHelper::__codeGeneration(Config::get('constant.LOYALTY'),Config::get('constant.LOYALTY_CODE'));
        $loyalty->loyalty_status = Config::get('constant.LOYALTY_STATUS.CREATED');
        $loyalty->max_checkin = $data['max_checkin'];
        $loyalty->max_bill_amount = $data['max_bill_amount'];
        //$loyalty->offer_type = $data['offer_type'];
        $loyalty->zoin_point = $data['zoin_point'];
        $loyalty->description = $data['description'];
        $loyalty->vendor_id = $data['vendor_id'];
        $loyalty->save();

        return ( isset( $loyalty->id ) && !empty( $loyalty->id ) ? $loyalty->id : '' );

    }

    public static function isCheckLoyaltyCount( $data ) {

        $count = Loyalty::where(['vendor_id' => $data])->count();

        return ( isset( $count ) && !empty( $count ) ? $count : '' );
    }

    public static function isCheckLoyaltyOpenCount( $data ) {
        
        $count = Loyalty::where(['vendor_id' => $data])->where(['loyalty_status' => 'Open'])->count();

        return ( isset( $count ) && !empty( $count ) ? $count : '' );
    }

    public static function getLoyaltyDetails( $vendorId ) {
        
        $data = Loyalty::where(['vendor_id' => $vendorId])->select("max_checkin","zoin_point","max_bill_amount","loyalty_id","loyalty_status","description","created_at")->orderBy('id', 'DESC')->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    }

    public static function getLoyaltySubmitDetails( $vendorId ) {
        
        $data = Loyalty::where(['vendor_id' => $vendorId])
                ->select("max_checkin","zoin_point","max_bill_amount","loyalty_id","loyalty_status","description","created_at")
                ->whereIn('loyalty_status', [Config::get('constant.LOYALTY_STATUS.CREATED'),Config::get('constant.LOYALTY_STATUS.INACTIVE'),Config::get('constant.LOYALTY_STATUS.OPEN')])
                ->orderBy('id', 'DESC')
                ->get();
 
        return ( isset( $data ) && !empty( $data ) ? $data : '' );    
    }	

    public static function getAllLoyaltySubmitDetails( $vendorId = NULL ) {
        
        $query  = DB::table('merchant_details as m')
                ->select("m.company_name","m.email_id","m.contact_person","m.mobile_number","m.vendor_id","m.description","m.website","m.start_time","m.end_time","a.address","a.city","l.max_checkin","l.max_bill_amount","l.zoin_point","l.description as ldescription","l.loyalty_id","l.loyalty_status")
                ->leftjoin('address as a', 'm.address_id', '=', 'a.address_id')
                ->leftjoin('loyalty as l', 'm.vendor_id', '=', 'l.vendor_id')
                ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                ->orderBy('m.id', 'DESC');
         
               if($vendorId) {
                    $query->where('m.vendor_id', '=', $vendorId);
               }

               $data = $query->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

    public static function checkLoyaltyCombination( $data ) {
        
         $result = Loyalty::where(['loyalty_id' => $data['loyalty_id']])->where(['zoin_point' => $data['zoin_point']])->where(['max_checkin' => $data['max_checkin']])->where(['max_bill_amount' => $data['max_bill_amount']])->count();   
        
        return ( isset( $result ) && !empty( $result ) ? $result : '' );
    }
 
   public static function getMerchantLoyaltyDetails( $mobileNumber = NULL ) {
        
        $query  = DB::table('merchant_details as m')
                ->select("m.company_name","m.email_id","m.contact_person","m.mobile_number","m.vendor_id","m.description","m.website","m.start_time","m.end_time","l.max_checkin","l.max_bill_amount","l.zoin_point","l.loyalty_id","l.loyalty_status")
                ->leftjoin('loyalty as l', 'm.vendor_id', '=', 'l.vendor_id')
                ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                ->orderBy('m.id', 'DESC');
        
            if($mobileNumber) {
                $query->where('m.mobile_number', '=', $mobileNumber);
            }

            $data = $query->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );

    }

    public static function getloyaltyDetail( $vendorId ) {
        
        $data = Loyalty::where(['vendor_id' => $vendorId])->select("loyalty_id","loyalty_status","max_checkin","max_bill_amount","zoin_point","description","vendor_id","created_at","updated_at")->first();

        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    }

    public static function getloyaltyPopupDetail( $vendorId ) {
        
        $data = Loyalty::where(['vendor_id' => $vendorId])->select("loyalty_id","loyalty_status","max_checkin","max_bill_amount","zoin_point","description","vendor_id","created_at","updated_at")->get();

        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    }

    public static function getCliamedLoyaltySubmitDetails( $vendorId ) {
        
            $data = DB::table('merchant_details as m')
                ->select("m.vendor_id","m.company_name","m.email_id","m.contact_person","m.mobile_number","l.max_checkin","l.max_bill_amount","l.loyalty_id","l.loyalty_status")
                ->join('loyalty as l', 'm.vendor_id', '=', 'l.vendor_id')
                ->where(['l.vendor_id' => $vendorId])
                ->where(['l.loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                ->orderBy('m.id', 'DESC')
                ->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

    public static function getLoyaltyOpenDetails( $vendorId ) {
        
        $data = Loyalty::where(['vendor_id' => $vendorId])
                ->select("max_checkin","zoin_point","max_bill_amount","loyalty_id","loyalty_status","description","created_at")
                ->where(['loyalty_status' => Config::get('constant.LOYALTY_STATUS.OPEN')])
                ->orderBy('id', 'DESC')
                ->get();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

    public static function getMerchantStatusLoyaltyDetails( $loyaltyId ) {
        
        $data = Loyalty::where(['loyalty_id' => $loyaltyId])
                ->select("max_checkin","zoin_point","max_bill_amount","loyalty_id","loyalty_status","description","vendor_id","created_at")
                ->first();
        
        return ( isset( $data ) && !empty( $data ) ? $data : '' );
    
    }

}
