<?php
namespace App;
use DB;
use Config;
use App\Helpers\CustomHelper;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "transactions";
    
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
        'transaction_id', 'vendor_id', 'user_id', 'transaction_type', 'creation_date', 'transaction_status', 'loyalty_id', 'user_checkin', 'user_bill_amount'
    ];


    public static function saveNewTransaction( $data, $amount ){
        
       /* if( !empty( $data['image'] ) )  {
            $imageContent = Config::get('constant.BASE_ENCODE').$data['image'];          
            $destinationPath = public_path('/images//');
            $image_parts = explode(";base64,", $imageContent);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = time().'.png';
            $fullPath = url('/')."/images/".$fileName;
            $file = $destinationPath."".$fileName;
            file_put_contents($file, $image_base64); 
        } */
        
        $userTransaction = new Transaction();
        $userTransaction->transaction_id = CustomHelper::__codeGeneration(Config::get('constant.ZOINUSER.TRANSACTION'),Config::get('constant.FORMAT-CODE.TRANSACTION_CODE'));
        $userTransaction->vendor_id = $data['vendor_id'];
        $userTransaction->user_id = $data['user_id'];
        $userTransaction->transaction_type = Config::get('constant.TRANSACTION_TYPE');
        $userTransaction->creation_date =  date('Y-m-d H:i:s');
        $userTransaction->transaction_status = Config::get('constant.LOYALTY_STATUS.APPROVED');
        $userTransaction->loyalty_id = ( ( isset($data['loyalty_id']) && !empty($data['loyalty_id']) ) ? $data['loyalty_id'] : '' );
        $userTransaction->user_checkin = Config::get('constant.NUMBER.ONE');
        $userTransaction->user_bill_amount = ( ( isset($amount) && !empty($amount) ) ? $amount : '' );            
        $userTransaction->bill_path = CustomHelper::baseEncode64Image( $data['image'] );            
        $userTransaction->save();

        return ( isset($userTransaction->id) && !empty($userTransaction->id) ? $userTransaction->id : '' );
    }

    public static function getUserTransactions( $data ) {
        
        $records = Transaction::where(['vendor_id' => $data[0]->vendor_id])->where(['user_id' => $data[0]->user_id])->where(['loyalty_id' => $data[0]->loyalty_id])->where(['status' => Config::get('constant.NUMBER.ZERO')])->sum('user_bill_amount');
        
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getUserAllTransactions( $data ) {
         
        $records = Transaction::where(['vendor_id' => $data->vendor_id])->where(['user_id' => $data->user_id])->where(['loyalty_id' => $data->loyalty_id])->where(['transaction_status' => Config::get('constant.LOYALTY_STATUS.APPROVED')])->sum('user_bill_amount');
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getUserTransactionsCount( $data ) {
         
        $records = Transaction::where(['vendor_id' => $data[0]->vendor_id])->where(['user_id' => $data[0]->user_id])->where(['loyalty_id' => $data[0]->loyalty_id])->where(['status' => Config::get('constant.NUMBER.ZERO') ])->count();
       
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getTransactionDetails( $transactionId ) {

        $records = Transaction::where(['id' => $transactionId])->first();
        return ( isset( $records ) && !empty( $records ) ? $records : '' );
    }

    public static function getTransactionsDetails( $vendorId, $userId, $loyaltyId ) {
 
        $records = Transaction::where(['vendor_id' => $vendorId])->where(['user_id' => $userId])->where(['loyalty_id' => $loyaltyId])->orderBy('id', 'DESC')->get();
        
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getMerchantTransactionsDetails( $vendorId ) {
 
        $records = Transaction::where(['vendor_id' => $vendorId])->orderBy('id', 'DESC')->get();
        
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getMerTransLoyaltyDetails( $vendorId, $loyaltyId ) {
 
        $records = Transaction::where(['vendor_id' => $vendorId, 'loyalty_id' => $loyaltyId])->orderBy('id', 'DESC')->get();
        
        return ( isset( $records ) && !empty( $records ) ? $records : 0 );
    }

    public static function getMerchantNotificationsDetails( $data ){
        
        $records = DB::table('merchant_details as m')
                    ->select("m.company_name","m.vendor_id","t.transaction_id","t.creation_date")
                    ->join('transactions as t', 'm.vendor_id', '=', 't.vendor_id')
                    ->where('m.vendor_id', '=', $data['vendor_id'])
                    ->orderBy('m.id', 'DESC')
                    ->get();

        return ( isset( $records ) && !empty( $records ) ? $records : 0 );         

    }

   
}
