<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Helpers\CustomHelper;
use App\Traits\CustomMessage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\MerchantDetail;
use App\MerchantTags;
use App\TagMerchants;
use App\MerchantImage;
use App\MerchantBankDetail;
use App\MerchantFeatureDetail;
use App\MerchantFeatureImage;
use App\MerchantSocialMedia;
use App\MerchantPoint;
use App\UserDetail;
use App\Address;
use App\Credential;
use App\MobileOtp;
use App\ForgotOtp;
use App\ZoinBalance;
use App\BusinessRule;
use App\CheckinLimit;
use App\BusinessType;
use App\LoyaltyBalance;
use App\RedeemCode;
use App\Transaction;
use App\Notification;
use App\Loyalty;
use Config;
use Mail;
use DB;

class MerchantApiController extends Controller
{
  
    use CustomMessage;
   
    public $successStatusCode = 200; ////Success status code
    public $failureStatusCode = 400; //failure status code
    public $successStatus = 'true'; //success
    public $failureStatus = 'false'; //failure

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function registration(Request $request) {
 
        $input = $request->all();
 		
         // run the validation rules on the inputs from the form
         $validator = Validator::make($input, MerchantDetail::$regisMerchantRules);
         // if the validator fails, redirect back to the form
         if ($validator->fails()) {
		 
		    $errors = $validator->messages()->toArray();
            $message = CustomHelper::checkEmailMobileNoExists($errors);
           
            // If validation falis redirect back to login.
            return response()->json(['success' => $this->failureStatus, 'message' => $message], $this->failureStatusCode );
			 
         } else {
           
            $credentialDetailSave = new Credential();
            $credentialId = $credentialDetailSave->saveCredentials( $input );

            $addressDetailSave = new Address();
            $addressId = $addressDetailSave->saveAddress( $input );

            $MerchantDetailSave = new MerchantDetail();
            $MerchantId = $MerchantDetailSave->saveMerchants( $input, $addressId);

            if( isset( $MerchantId ) && !empty( $MerchantId ) ) {  
                
                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( Input::get('mobile_number') );

                $merchantPoint = new MerchantPoint();
                $amt = $merchantPoint->getMerchantPoints();
                $merPoint = ( isset( $amt->amount ) && !empty( $amt->amount ) ? $amt->amount : 0 ); 
                
                $MerchantZoinBal = new ZoinBalance();
                $MerchantZoinBal->merchantZoinBalanceCreate( $vendors['vendor_id'], $merPoint );
                
                if( isset( $vendors ) && !empty( $vendors ) ) {
                    CustomHelper::sendEmailNotification($MerchantId, Config::get('constant.ZOINUSER.ADMIN'));
                    CustomHelper::sendEmailNotification($MerchantId, Config::get('constant.ZOINUSER.MERCHANT'));
                    $content = Config::get('constant.NOTIFICATION.SMS.REGISTER');
                    CustomHelper::sendSmsService( Input::get('mobile_number'), $content );  // send sms
                }   
                // print_r(error_get_last());
                // If Merchant save Success.
                return response()->json(['success' => $this->successStatus, 'message' => $this->printMerchantRegisterSuccess(), 'vendorId' => $vendors['vendor_id'] ], $this->successStatusCode );	    

            } else {

                // If Merchant save falis.
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printMerchantRegisterFalse() ], $this->failureStatusCode );

            } 
        
        } 

    }

    public function doLogin(Request $request) {      
        
        $credentials = [
            'mobile_number' => Input::get('mobile_number'),
            'password' => Input::get('password')
        ];
         
         $credentialDetail = new Credential();
         $mobileNoExists = $credentialDetail->checkMobileNoExists( Input::get('mobile_number') );

        if(empty( $mobileNoExists) ) {
 
             return response()->json(['success' => $this->failureStatus, 'message' => $this->printInvalidMobileNo() ], $this->failureStatusCode );
 
        } else {
 
             if ( Hash::check( Input::get('password'), $mobileNoExists->password ) ) {

                if( !empty( $mobileNoExists->is_mobile_verified ) && $mobileNoExists->is_mobile_verified == Config::get('constant.NUMBER.ONE') ) { 

                    $merchantDetail = new MerchantDetail();
                    $merchant = $merchantDetail->ischeckMobileverified( Input::get('mobile_number') );
                    
                    if( !empty( $merchant->is_admin_approved ) && $merchant->is_admin_approved == Config::get('constant.NUMBER.ONE') ) { 
                        
                        return response()->json(['success' => $this->successStatus, 'message' => $this->printZoinMember() ], $this->successStatusCode );
                    
                    } else {
    
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printCustomerSupport() ], $this->failureStatusCode );
                    } 
                
                } else {

                    //Send SMS Mobile verification Process
                    $this->__sendSMSMobileVerified( Input::get('mobile_number') );
                    
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoNotVerified() ], $this->failureStatusCode );
                }     
             
            } else {
 
                 return response()->json(['success' => $this->failureStatus, 'message' => $this->printInvalidMobileNoAndPassword() ], $this->failureStatusCode );
            }
        } 
         
    }

    public function resetPassword(Request $request)
    {
       
        $input = $request->all();

        $mobileNumber = $input['mobile_number'];

        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {

            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );

            /* echo "<pre>";
            print_r($mobileNoExists);
            exit; */

            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {      

                if( !empty( $mobileNoExists->is_mobile_verified ) && $mobileNoExists->is_mobile_verified == Config::get('constant.NUMBER.ONE') ) { 
                
                    //Send SMS Mobile verification Process
                    $this->__sendSMSMobileVerified( $mobileNumber );

                    return response()->json(['success' => $this->successStatus, 'message' => $this->printOtpGenerationSuccess() ], $this->successStatusCode );        

                } else {
                    
                    //Send SMS Mobile verification Process
                    $this->__sendSMSMobileVerified( $mobileNumber );
                    
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printForgotMobileNotVerified() ], $this->failureStatusCode );
                }  
            
            }  else {
                //Enter the Correct Mobile Number 
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }   

        } else {
             
             return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }
    
    }

    public function __sendSMSMobileVerified( $mobileNumber ) {
        
        // $generateOTPToken
        $otpGenerate = CustomHelper::__otpGeneration( $mobileNumber );                
        // send sms
        CustomHelper::sendSms( $mobileNumber, $otpGenerate );
        //Exists OTP Remove 
        CustomHelper::isExistOTPRemove( $mobileNumber, $otpGenerate );

        return $otpGenerate;
    }

    public function verifyOtp(Request $request) {
     
        $input = $request->all();
       
        $mobileNumber = $input['mobile_number'];
        $otp = $input['otp'];

        if( isset( $otp ) && !empty( $otp ) ) {    

            $mobileOtp = new MobileOtp();
            $merchantMobileNoOTP = $mobileOtp->isCheckMobileNoExistsOTP( $mobileNumber, $otp );
            
            if ( isset( $merchantMobileNoOTP ) && !empty( $merchantMobileNoOTP ) )  {
                
                CustomHelper::isCheckLoginActivateUser( $mobileNumber);

                return response()->json(['success' => $this->successStatus, 'message' => $this->printOTPSuccess() ], $this->successStatusCode );

            } else {
                
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPWrong() ], $this->failureStatusCode );
            }

        } else {
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPMissing() ], $this->failureStatusCode );
        }

    }


    public function updatePassword(Request $request) {

        $input = $request->all();

        $mobileNumber = $input['mobile_number'];

        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {

            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
       
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $updatePassword =  Credential::where( [ 'mobile_number' => $mobileNumber ] )->update( [ 'password' => Hash::make( $input['new_password'] )] );

                if (! $updatePassword ) {
                    
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printPasswordFailure() ], $this->failureStatusCode );

                } else {

                    return response()->json(['success' => $this->successStatus, 'message' => $this->printPasswordSuccess() ], $this->successStatusCode );
                }
           
            }else {     

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }            
    
    }


    /* public function loyaltyOfferType(){

        $response = array();
        $businessRule = new BusinessRule();
        //$businessType = new BusinessType();
        $checkinLimit = new CheckinLimit();
        $businessBillAmts = $businessRule->getBusinessBillAmount( );
        $checkinLimits = $checkinLimit->getMaximumCheckinAvailable( );
        
        if( isset( $businessBillAmts ) && !empty( $checkinLimits ) ) {  

            $offerType[0]['offer_type'] = "zoin";
            $response['businessAmt'] = $businessBillAmts;
            $response['checkinLimit'] = $checkinLimits;
            $response['offerType'] = $offerType;
    
            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyOfferFailure() ], $this->failureStatusCode );
        }
       
    } */

    public function loyaltyOfferType(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
              
                $businessRule = new BusinessRule();
                $businessZoinPoints = $businessRule->getBusinessBillAmount();        
                
                $MerchantZoinBal = new ZoinBalance();
                $merchantbal = $MerchantZoinBal->getMerchantBalance( $vendors['vendor_id'] );

                $balance = ( isset( $merchantbal->zoin_balance ) && !empty( $merchantbal->zoin_balance ) ? $merchantbal->zoin_balance : Config::get('constant.NUMBER.ZERO') );
                $merBalance = ( isset( $balance ) && !empty( $balance ) ? $balance : Config::get('constant.NUMBER.ZERO') );
                
                if( ! $businessZoinPoints->isEmpty() ) {
                        
                        $response = array();
                        foreach($businessZoinPoints as $businessZoinPoint){
                            $data = array();
                            $data['max_bill_amount'] = ( isset( $businessZoinPoint['max_loyalty_amount'] ) && !empty( $businessZoinPoint['max_loyalty_amount'] ) ? $businessZoinPoint['max_loyalty_amount'] : Config::get('constant.NUMBER.ZERO') );
                            $data['zoin_point'] = ( isset( $businessZoinPoint['zoin_points'] ) && !empty( $businessZoinPoint['zoin_points'] ) ? $businessZoinPoint['zoin_points'] : Config::get('constant.NUMBER.ZERO') );
                            $data['mer_balance'] = ( isset( $merBalance ) && !empty( $merBalance ) ? $merBalance : Config::get('constant.NUMBER.ZERO') );
                            $data['total_loyalty_bal'] = CustomHelper::merchantLoyaltyBalance( $merBalance , $businessZoinPoint['zoin_points'] );    
                            array_push($response,$data);
                        }
                         
                        return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                        
                } else {

                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
                } 

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }


    public function addLoyalty(Request $request) {
        
        $input = $request->all();
        $vendorId = $input['vendor_id'];
        $loyaltyCheck = new Loyalty();
        $loyaltyCount = $loyaltyCheck->isCheckLoyaltyCount( $vendorId );
        
        if( $loyaltyCount >= Config::get('constant.NUMBER.ONE') ) {
              // If loyalty More than one.
              return response()->json(['success' => $this->failureStatus, 'message' => $this->printAddMoreLoyalty() ], $this->failureStatusCode );
        } else {

            $loyaltyDetailSave = new Loyalty();
            $loyaltyId = $loyaltyDetailSave->saveNewloyaltyDetail( $input );
            
            if( isset($loyaltyId) && !empty($loyaltyId) ) {
               
               $merloyaltyBalance = new LoyaltyBalance();
               $existsMerchantRecs = $merloyaltyBalance->getMerchantloyaltyCount( $vendorId ); 

               if( isset( $existsMerchantRecs ) && !empty( $existsMerchantRecs ) ) {  
                    $updateBalance = new LoyaltyBalance();
                    $updateBalance->merchantLoyaltyBalanceIncrement( $vendorId );
               } else {
                    $merloyaltyBalance = new LoyaltyBalance();
                    $merloyaltyBalance->saveMerchantloyaltyCount( $input );
               }
                
                $getloyDetails = new Loyalty();
                $records = $getloyDetails->getloyaltyDetail( $vendorId );
                if( isset( $records ) && !empty( $records ) ) {
                    CustomHelper::sendEmailNotification($records['loyalty_id'] ,"Loyalty");
                }
                //$content = Config::get('constant.NOTIFICATION.MERCHANT.LOYALTY_SUBMIT')." "."Vendor : $vendorId  Loyalty  : $loyaltyId";
              
                $getMerDetails = new MerchantDetail();
                $data = $getMerDetails->getMerchantDetails( $vendorId );
                if( isset( $data ) && !empty( $data ) ) {
                    $content = Config::get('constant.NOTIFICATION.SMS.LOYALTY_SUBMIT');
                    $mobileNumber = ( isset( $data->mobile_number ) && !empty( $data->mobile_number ) ? $data->mobile_number : '' );
                    CustomHelper::sendSmsService( $mobileNumber, $content );   // send sms
                }    
                $notifiDetailSave = new Notification();
                $notifiDetailSave->saveLoyaltyNotificationDetail( $records );
                
                // If loyalty save Success.
                return response()->json(['success' => $this->successStatus, 'message' => $this->printLoyaltySuccess() ], $this->successStatusCode );	    
    
            } else {
    
                // If loyalty save falis.
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyFailure() ], $this->failureStatusCode );
            }  

        }          

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function SideBarLoyaltyList(Request $request) {
        
        $input = $request->all();

        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
            
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $MerchantDetailSave = new MerchantDetail();
                $sideBarLists = $MerchantDetailSave->getSidebarProfileDetails( $input );

                $response = array();        
                foreach( $sideBarLists as $i => $sideBarList ) { 
                    
                    $response['vendor_id'] =  $sideBarLists->vendor_id;
                    $response['company_name'] =  CustomHelper::getCamelCase($sideBarLists->company_name);
                    $response['first_letter'] =  CustomHelper::getFirstLetterReturn($sideBarLists->company_name);
                    $response['business_type'] = $sideBarLists->business_type;
                    $response['user_type'] = Config::get('constant.MERCHANT');
                    $response['key1'] = ( ( $sideBarLists->is_admin_approved != Config::get('constant.NUMBER.THREE') ) ? CustomHelper::isCheckMerchantStatus( $sideBarLists->is_admin_approved ) : '');
                    $response['key2'] = ( ( $sideBarLists->is_admin_approved == Config::get('constant.NUMBER.THREE') ) ? CustomHelper::isCheckMerchantStatus( $sideBarLists->is_admin_approved ) : '');
                     
                } 

                if( isset( $response ) && !empty( $response ) ) {    
                    
                    return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                    
                } else {
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printProfileNotCreated() ], $this->failureStatusCode );
                }
            
            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            } 

        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        } 

    }    


   /* public function LoyaltyList(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                
                $loyaltyDetail = new Loyalty();
                $loyaltyDetails = $loyaltyDetail->getLoyaltyDetails( $vendors['vendor_id'] );        
                
                //$merchantPoint = new MerchantPoint();
                //$merchant = $merchantPoint->getMerchantPoints();  
                
                $MerchantZoinBal = new ZoinBalance();
                $merchantbal = $MerchantZoinBal->getMerchantBalance( $vendors['vendor_id'] );

                //echo "<pre>";
                //print_r($merchantbal); exit;

                $balance = ( isset( $merchantbal->zoin_balance ) && !empty( $merchantbal->zoin_balance ) ? $merchantbal->zoin_balance : Config::get('constant.NUMBER.ZERO') );
                $merBalance = ( isset( $balance ) && !empty( $balance ) ? $balance : Config::get('constant.NUMBER.ZERO') );
                
                if( ! $loyaltyDetails->isEmpty() ) {
                     
                    $loyaltySubmitDetail = new Loyalty();
                    $loyaltySubmitDetails = $loyaltySubmitDetail->getLoyaltySubmitDetails( $vendors['vendor_id'] ); 
                    
                    if( ! $loyaltySubmitDetails->isEmpty() ) {

                        $response = array();
                        foreach($loyaltySubmitDetails as $loyaltySubmitDetail){
                            $data = array();
                            $data['loyalty_id'] = ( isset( $loyaltySubmitDetail['loyalty_id'] ) && !empty( $loyaltySubmitDetail['loyalty_id'] ) ? $loyaltySubmitDetail['loyalty_id'] : Config::get('constant.EMPTY') );
                            $data['offer_type'] = ( isset( $loyaltySubmitDetail['offer_type'] ) && !empty( $loyaltySubmitDetail['offer_type'] ) ? $loyaltySubmitDetail['offer_type'] : Config::get('constant.EMPTY') );
                            $data['max_checkin'] = ( isset( $loyaltySubmitDetail['max_checkin'] ) && !empty( $loyaltySubmitDetail['max_checkin'] ) ? $loyaltySubmitDetail['max_checkin'] : Config::get('constant.EMPTY') );
                            $data['max_bill_amount'] = ( isset( $loyaltySubmitDetail['max_bill_amount'] ) && !empty( $loyaltySubmitDetail['max_bill_amount'] ) ? $loyaltySubmitDetail['max_bill_amount'] : Config::get('constant.EMPTY') );
                            $data['zoin_point'] = ( isset( $loyaltySubmitDetail['zoin_point'] ) && !empty( $loyaltySubmitDetail['zoin_point'] ) ? $loyaltySubmitDetail['zoin_point'] : Config::get('constant.EMPTY') );
                            $data['loyalty_status'] = ( isset( $loyaltySubmitDetail['loyalty_status'] ) && !empty( $loyaltySubmitDetail['loyalty_status'] ) ? CustomHelper::isCheckLoyaltyStatus( $loyaltySubmitDetail['loyalty_status'] ) : Config::get('constant.EMPTY') );
                            $data['key'] = ( isset( $loyaltySubmitDetail['loyalty_status'] ) && !empty( $loyaltySubmitDetail['loyalty_status'] ) ? CustomHelper::getLoyaltyStatusBasedKey( $loyaltySubmitDetail['loyalty_status'] ) : Config::get('constant.EMPTY') );
                            $data['mer_balance'] = ( isset( $merBalance ) && !empty( $merBalance ) ? $merBalance : Config::get('constant.NUMBER.ZERO') );
                            $data['total_loyalty_bal'] = CustomHelper::merchantLoyaltyBalance( $merBalance , $loyaltySubmitDetail['zoin_point'] );
                            $data['description'] = ( isset( $loyaltySubmitDetail['description'] ) && !empty( $loyaltySubmitDetail['description'] ) ? $loyaltySubmitDetail['description'] : Config::get('constant.EMPTY') );
                            // $data['date_format'] = CustomHelper::getZoinDateandTimeFormat( $loyaltySubmitDetail['created_at'] );
                            $data['date_format'] = ( isset( $loyaltySubmitDetail['created_at'] ) && !empty( $loyaltySubmitDetail['created_at'] ) ? CustomHelper::getZoinDateFormat( $loyaltySubmitDetail['created_at'] ) : Config::get('constant.EMPTY') );                            
                            $data['time_format'] = ( isset( $loyaltySubmitDetail['created_at'] ) && !empty( $loyaltySubmitDetail['created_at'] ) ? CustomHelper::getZoinTimeFormat( $loyaltySubmitDetail['created_at'] ) : Config::get('constant.EMPTY') );                            
                            array_push($response,$data);
                        }

                        return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode ); 

                    } else {       

                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyAccess() ], $this->failureStatusCode );
                    }
                    
                } else {

                   return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyNotCreated() ], $this->failureStatusCode );
                } 

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    } */

    public function LoyaltyList(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                
                $loyaltyDetail = new Loyalty();
                $loyaltyDetails = $loyaltyDetail->getLoyaltyDetails( $vendors['vendor_id'] );        
                
                if( ! $loyaltyDetails->isEmpty() ) {
                     
                    $loyaltySubmitDetail = new Loyalty();
                    $loyaltySubmitDetails = $loyaltySubmitDetail->getLoyaltySubmitDetails( $vendors['vendor_id'] ); 
                    
                    if( ! $loyaltySubmitDetails->isEmpty() ) {

                        $response = array();
                        foreach($loyaltySubmitDetails as $loyaltySubmitDetail){
                            $data = array();
                            $data['loyalty_id'] = ( isset( $loyaltySubmitDetail['loyalty_id'] ) && !empty( $loyaltySubmitDetail['loyalty_id'] ) ? $loyaltySubmitDetail['loyalty_id'] : Config::get('constant.EMPTY') );
                            //$data['offer_type'] = ( isset( $loyaltySubmitDetail['offer_type'] ) && !empty( $loyaltySubmitDetail['offer_type'] ) ? $loyaltySubmitDetail['offer_type'] : Config::get('constant.EMPTY') );
                            $data['max_checkin'] = ( isset( $loyaltySubmitDetail['max_checkin'] ) && !empty( $loyaltySubmitDetail['max_checkin'] ) ? $loyaltySubmitDetail['max_checkin'] : Config::get('constant.EMPTY') );
                            $data['max_bill_amount'] = ( isset( $loyaltySubmitDetail['max_bill_amount'] ) && !empty( $loyaltySubmitDetail['max_bill_amount'] ) ? $loyaltySubmitDetail['max_bill_amount'] : Config::get('constant.EMPTY') );
                            $data['zoin_point'] = ( isset( $loyaltySubmitDetail['zoin_point'] ) && !empty( $loyaltySubmitDetail['zoin_point'] ) ? $loyaltySubmitDetail['zoin_point'] : Config::get('constant.EMPTY') );
                            $data['loyalty_status'] = ( isset( $loyaltySubmitDetail['loyalty_status'] ) && !empty( $loyaltySubmitDetail['loyalty_status'] ) ? CustomHelper::isCheckLoyaltyStatus( $loyaltySubmitDetail['loyalty_status'] ) : Config::get('constant.EMPTY') );
                            $data['key'] = ( isset( $loyaltySubmitDetail['loyalty_status'] ) && !empty( $loyaltySubmitDetail['loyalty_status'] ) ? CustomHelper::getLoyaltyStatusBasedKey( $loyaltySubmitDetail['loyalty_status'] ) : Config::get('constant.EMPTY') );
                            $data['description'] = ( isset( $loyaltySubmitDetail['description'] ) && !empty( $loyaltySubmitDetail['description'] ) ? $loyaltySubmitDetail['description'] : Config::get('constant.EMPTY') );
                            // $data['date_format'] = CustomHelper::getZoinDateandTimeFormat( $loyaltySubmitDetail['created_at'] );
                            $data['date_format'] = ( isset( $loyaltySubmitDetail['created_at'] ) && !empty( $loyaltySubmitDetail['created_at'] ) ? CustomHelper::getZoinDateFormat( $loyaltySubmitDetail['created_at'] ) : Config::get('constant.EMPTY') );                            
                            $data['time_format'] = ( isset( $loyaltySubmitDetail['created_at'] ) && !empty( $loyaltySubmitDetail['created_at'] ) ? CustomHelper::getZoinTimeFormat( $loyaltySubmitDetail['created_at'] ) : Config::get('constant.EMPTY') );                            
                            array_push($response,$data);
                        }

                        return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode ); 

                    } else {       

                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyAccess() ], $this->failureStatusCode );
                    }
                    
                } else {

                   return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyNotCreated() ], $this->failureStatusCode );
                } 

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }

    
    /* public function ViewLoyaltyList(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                
                $loyaltyDetail = new Loyalty();
                $loyaltyDetails = $loyaltyDetail->getLoyaltySubmitDetails( $vendors['vendor_id'] );        
               
                if( ! $loyaltyDetails->isEmpty() ) {
                    //echo $loyaltyDetails[0]['loyalty_status']; exit;
                    $status = CustomHelper::isCheckLoyaltyStatus( $loyaltyDetails[0]['loyalty_status'] );
                    $key =  CustomHelper::getLoyaltyStatusBasedKey( $loyaltyDetails[0]['loyalty_status'] );
                    $loyalty_status = CustomHelper::isCheckLoyaltyStatus( $loyaltyDetails[0]['loyalty_status'] ) ;
                    if( $status == Config::get('constant.NOT_APPROVED') || $status == Config::get('constant.DELETED') ) {

                      return response()->json(['success' => $this->failureStatus, 'message' => $status ], $this->failureStatusCode );
                    
                    } else {
                        
                        $loyaltyDetails1['loyalty_status'] = $status;
                        $loyaltyDetails1['key'] =  $key;
                        $loyaltyDetails1['loyalty_status'] =  $loyalty_status;
                        $loyaltyDetails1['max_checkin'] =  $loyaltyDetails[0]['max_checkin'];
                        $loyaltyDetails1['zoin_point'] =  $loyaltyDetails[0]['zoin_point'];
                        $loyaltyDetails1['max_bill_amount'] =  $loyaltyDetails[0]['max_bill_amount'];
                        //$loyaltyDetails1['offer_type'] =  $loyaltyDetails[0]['offer_type'];
                        $loyaltyDetails1['loyalty_id'] =  $loyaltyDetails[0]['loyalty_id'];
                        $loyaltyDetails1['description'] =  $loyaltyDetails[0]['description'];
                       // $loyaltyDetails1['date_format'] = CustomHelper::getZoinDateFormat( $loyaltyDetails[0]['created_at'] );
                       // $loyaltyDetails1['time_format'] = CustomHelper::getZoinTimeFormat( $loyaltyDetails[0]['created_at'] );
                        unset($loyaltyDetails1['created_at']);
                        return response()->json(['success' => $this->successStatus, 'message' => $loyaltyDetails1], $this->successStatusCode );   
                    }
                    
                } else {

                   return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyNotCreated() ], $this->failureStatusCode );
                } 

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    } */

    public function ViewLoyaltyList(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                $MerchantZoinBal = new ZoinBalance();
                $merchantbal = $MerchantZoinBal->getMerchantBalance( $vendors['vendor_id'] );
                $balance = ( isset( $merchantbal->zoin_balance ) && !empty( $merchantbal->zoin_balance ) ? $merchantbal->zoin_balance : Config::get('constant.NUMBER.ZERO') );
                $merBalance = ( isset( $balance ) && !empty( $balance ) ? $balance : Config::get('constant.NUMBER.ZERO') );
                
                $loyaltyDetail = new Loyalty();
                $records = $loyaltyDetail->getloyaltyDetail( $vendors['vendor_id'] );
                        
                $data = array();
                if( isset( $records ) && !empty( $records ) ) {
                    $data['loyalty_id'] = ( isset( $records['loyalty_id'] ) && !empty( $records['loyalty_id'] ) ? $records['loyalty_id'] : Config::get('constant.EMPTY') );
                    $data['max_checkin'] = ( isset( $records['max_checkin'] ) && !empty( $records['max_checkin'] ) ? $records['max_checkin'] : Config::get('constant.EMPTY') );
                    $data['max_bill_amount'] = ( isset( $records['max_bill_amount'] ) && !empty( $records['max_bill_amount'] ) ? $records['max_bill_amount'] : Config::get('constant.EMPTY') );
                    $data['zoin_point'] = ( isset( $records['zoin_point'] ) && !empty( $records['zoin_point'] ) ? $records['zoin_point'] : Config::get('constant.EMPTY') );
                    $data['loyalty_status'] = ( isset( $records['loyalty_status'] ) && !empty( $records['loyalty_status'] ) ? CustomHelper::isCheckLoyaltyStatus( $records['loyalty_status'] ) : Config::get('constant.EMPTY') );
                    $data['key'] = ( isset( $records['loyalty_status'] ) && !empty( $records['loyalty_status'] ) ? CustomHelper::getLoyaltyStatusBasedKey( $records['loyalty_status'] ) : Config::get('constant.EMPTY') );
                    $data['mer_balance'] = ( isset( $merBalance ) && !empty( $merBalance ) ? $merBalance : Config::get('constant.NUMBER.ZERO') );
                    $data['total_loyalty_bal'] = CustomHelper::merchantLoyaltyBalance( $merBalance , $records['zoin_point'] );
                    $data['description'] = ( isset( $records['description'] ) && !empty( $records['description'] ) ? $records['description'] : Config::get('constant.EMPTY') );   
                }  
                
                return response()->json(['success' => $this->successStatus, 'message' => $data], $this->successStatusCode ); 

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }


    public function MerchantProfileList(Request $request) {
        
        $input = $request->all();
        $mobileNumber = $input['mobile_number'];
    
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
        
                $MerchantDetailSave = new MerchantDetail();
                $sideBarLists = $MerchantDetailSave->getProfileDetails( $input );

                $loyaltyCheck = new Loyalty();
                $loyaltyCount = $loyaltyCheck->isCheckLoyaltyOpenCount( $sideBarLists->vendor_id );
                
                $merloyaltyBalance = new LoyaltyBalance();
                $claimedRec = $merloyaltyBalance->getMerchantloyaltyCount( $sideBarLists->vendor_id );

                /* echo "<pre>";
                print_r($claimedRec);
                exit; */

                $response = array();        
                foreach( $sideBarLists as $i => $sideBarList ) { 
                    
                    $response['vendor_id'] =  $sideBarLists->vendor_id;
                    $response['company_name'] =  CustomHelper::getCamelCase($sideBarLists->company_name);
                    $response['first_letter'] =  CustomHelper::getFirstLetterReturn($sideBarLists->company_name);
                    $response['business_type'] = ( isset( $sideBarLists->business_type ) && !empty( $sideBarLists->business_type ) ? $sideBarLists->business_type : ' ');
                    $response['user_type'] = Config::get('constant.MERCHANT');
                    $response['zoin_balance'] = ( isset( $sideBarLists->zoin_balance ) && !empty( $sideBarLists->zoin_balance ) ? $sideBarLists->zoin_balance : 0);
                    $response['city'] = ( isset( $sideBarLists->city ) && !empty( $sideBarLists->city ) ? $sideBarLists->city : ' ');
                    
                } 
                
                if( isset( $response ) && !empty( $response ) ) {    
                    $response['loyalty_count'] = ( isset( $loyaltyCount ) && !empty( $loyaltyCount ) ? $loyaltyCount : 0);
                    $response['loyalty_claimed'] = ( isset( $claimedRec['claimed_loyalty'] ) && !empty( $claimedRec['claimed_loyalty'] ) ? $claimedRec['claimed_loyalty'] : 0 ); 
 
                    return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                    
                } else {
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printProfileNotCreated() ], $this->failureStatusCode );
                }

            } else { 
                    
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  
    
    }

    public function confirm($confirmation_code) {
        
        if( ! $confirmation_code ) {        
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printNetworkFailed() ], $this->failureStatusCode );
        }
       
        $MerchantDetail = new MerchantDetail();
        $MerchantDetails = $MerchantDetail->getVendorDetails( $confirmation_code );
       
        if ( ! $MerchantDetails ) {
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printNetworkFailed() ], $this->failureStatusCode );
        }

        $MerchantDetails->is_email_verified = 1;       
        $MerchantDetails->confirmation_code = '';       
        $MerchantDetails->save();

        return response()->json(['success' => $this->successStatus, 'message' => printMobileNoVerified() ], $this->successStatusCode );
    }

    /* public function reSendOtpGeneration(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                //Send SMS Mobile verification Process
                $this->__sendSMSMobileVerified( $mobileNumber );

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    } */

    public function reSendOtpGeneration(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
             //Send SMS Mobile verification Process
             $this->__sendSMSMobileVerified( $mobileNumber );

        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function MerchantEditProfile(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
      
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
              $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
               
              if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
                
                $merchantDetail = new MerchantDetail();
                $merchant = $merchantDetail->ischeckMobileverified( Input::get('mobile_number') );
                
                if(!empty( $input['tag_name1'] ) ) {
                    $merchantDetails = new MerchantDetail();
                    $merchantDetails->updateDescription( $input );
                }
                if(!empty( $input['tag_name1'] ) || !empty( $input['tag_name2'] ) || !empty( $input['tag_name3'] ) || !empty( $input['tag_name4'] ) ) {
                    $removeTag = new TagMerchants();
                    $removeTag->removeTags( $merchant['vendor_id'] );
                }                 
                if(!empty( $input['tag_name1'] ) ) {
                    CustomHelper::checkMerchantTags( $input['tag_name1'] ,$merchant['vendor_id'] );
                }
                if(!empty( $input['tag_name2'] ) ) {
                    CustomHelper::checkMerchantTags( $input['tag_name2'] ,$merchant['vendor_id'] );
                }
                if(!empty( $input['tag_name3'] ) ) {
                    CustomHelper::checkMerchantTags( $input['tag_name3'] ,$merchant['vendor_id'] );
                }
                if(!empty( $input['tag_name4'] ) ) {
                    CustomHelper::checkMerchantTags( $input['tag_name4'] ,$merchant['vendor_id'] );
                }

                $notifiDetailSave = new Notification();
                $notifiDetailSave->saveEditProfileNotification( $merchant );
                
               /* $MerchantDetailSave = new MerchantDetail();
                $addressId = $MerchantDetailSave->updateMerchants( $input );
        
                $addressDetailSave = new Address();
                $addressId = $addressDetailSave->updateAddress( $input, $addressId );

                $MerchantDetail = new MerchantDetail();
                $records = $MerchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                
                $getbankDetail = new MerchantBankDetail();
                $data = $getbankDetail->getMerchantBankDetails( $records['vendor_id'] );

                if( isset( $data ) && !empty( $data ) ) {
                    $updatebankDetailSave = new MerchantBankDetail();
                    $updatebankDetailSave->updateMerchantBankDetails( $input, $records['vendor_id'] );
                } else {
                    $bankDetailSave = new MerchantBankDetail();
                    $bankDetailSave->saveMerchantBankDetails( $input, $records['vendor_id'] );
                }  */ 
                
                return response()->json(['success' => $this->successStatus, 'message' => $this->printProfileUpdated() ], $this->successStatusCode );
  
              } else { 
  
                  return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
              }    
          
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function merchantEditProfileList(Request $request) {
     
        $input = $request->all();
       
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
        
                $MerchantDetailSave = new MerchantDetail();
                $MerchantDetails = $MerchantDetailSave->getEditProfileDetails( $input );
                
                //echo "<pre>";
                //print_r($MerchantDetails); exit;

                if( isset( $MerchantDetails ) && !empty( $MerchantDetails ) ) {    
                    
                    $response = array();
                    foreach($MerchantDetails as $key => $MerchantDetail){
                        
                        $getProfileImage = new MerchantImage();
                        $images = $getProfileImage->getProfileImageDetails( $MerchantDetail->vendor_id );
                        $response['image'] = ( isset( $images['profile_image'] ) && !empty( $images['profile_image'] ) ? $images['profile_image'] : asset('/assets/images/zoin_empty.png'));
                        $response['profile_images'] = CustomHelper::merchantProfilePhotoDetails( $MerchantDetail->vendor_id );
                        $response['company_name'] = ( isset( $MerchantDetail->company_name ) && !empty( $MerchantDetail->company_name ) ? $MerchantDetail->company_name : Config::get('constant.EMPTY') );
                        $response['first_letter'] = CustomHelper::getFirstLetterReturn($MerchantDetail->company_name);
                        $response['company_name'] = ( isset( $MerchantDetail->company_name ) && !empty( $MerchantDetail->company_name ) ? $MerchantDetail->company_name : Config::get('constant.EMPTY') );
                        $response['vendor_id'] = ( isset( $MerchantDetail->vendor_id ) && !empty( $MerchantDetail->vendor_id ) ? $MerchantDetail->vendor_id : Config::get('constant.EMPTY') );
                        $response['email_id'] = ( isset( $MerchantDetail->email_id ) && !empty( $MerchantDetail->email_id ) ? $MerchantDetail->email_id : Config::get('constant.EMPTY') );
                        $response['contact_person'] = ( isset( $MerchantDetail->contact_person ) && !empty( $MerchantDetail->contact_person ) ? $MerchantDetail->contact_person : Config::get('constant.EMPTY') );
                        $response['mobile_number'] = ( isset( $MerchantDetail->mobile_number ) && !empty( $MerchantDetail->mobile_number ) ? "Mobile Number : ".$MerchantDetail->mobile_number : Config::get('constant.EMPTY') );
                        $response['address'] = ( isset( $MerchantDetail->address ) && !empty( $MerchantDetail->address ) ? $MerchantDetail->address : Config::get('constant.EMPTY') );
                        $response['latitude'] = ( isset( $MerchantDetail->latitude ) && !empty( $MerchantDetail->latitude ) ? $MerchantDetail->latitude : Config::get('constant.EMPTY') );
                        $response['longitude'] = ( isset( $MerchantDetail->longitude ) && !empty( $MerchantDetail->longitude ) ? $MerchantDetail->longitude : Config::get('constant.EMPTY') );                        
                        $response['city'] = ( isset( $MerchantDetail->city ) && !empty( $MerchantDetail->city ) ? $MerchantDetail->city : Config::get('constant.EMPTY') );
                        $response['gst_number'] = ( isset( $MerchantDetail->gst_number ) && !empty( $MerchantDetail->gst_number ) ? $MerchantDetail->gst_number : Config::get('constant.EMPTY') );
                        $response['author_number'] = ( isset( $MerchantDetail->author_number ) && !empty( $MerchantDetail->author_number ) ? $MerchantDetail->author_number : Config::get('constant.EMPTY') );
                        $response['pan_number'] = ( isset( $MerchantDetail->pan_number ) && !empty( $MerchantDetail->pan_number ) ? $MerchantDetail->pan_number : Config::get('constant.EMPTY') );
                        $response['ifsc_code'] = ( isset( $MerchantDetail->ifsc_code ) && !empty( $MerchantDetail->ifsc_code ) ? $MerchantDetail->ifsc_code : Config::get('constant.EMPTY') );
                        $response['account_number'] = ( isset( $MerchantDetail->account_number ) && !empty( $MerchantDetail->account_number ) ? $MerchantDetail->account_number : Config::get('constant.EMPTY') );
                        $response['account_name'] = ( isset( $MerchantDetail->account_name ) && !empty( $MerchantDetail->account_name ) ? $MerchantDetail->account_name : Config::get('constant.EMPTY') );
                        $response['bank_name'] = ( isset( $MerchantDetail->bank_name ) && !empty( $MerchantDetail->bank_name ) ? $MerchantDetail->bank_name : Config::get('constant.EMPTY') );
                        $response['bank_address'] = ( isset( $MerchantDetail->bank_address ) && !empty( $MerchantDetail->bank_address ) ? $MerchantDetail->bank_address : Config::get('constant.EMPTY') );
                        $response['account_type'] = ( isset( $MerchantDetail->account_type ) && !empty( $MerchantDetail->account_type ) ? $MerchantDetail->account_type : Config::get('constant.EMPTY') );
                        $response['description'] = ( isset( $MerchantDetail->description ) && !empty( $MerchantDetail->description ) ? $MerchantDetail->description : Config::get('constant.EMPTY') );
                        $response['website'] = ( isset( $MerchantDetail->website ) && !empty( $MerchantDetail->website ) ? "Website : ".$MerchantDetail->website : Config::get('constant.EMPTY') );
                        //$response['social'] = ( isset( $MerchantDetail->social ) && !empty( $MerchantDetail->social ) ? $MerchantDetail->social : Config::get('constant.EMPTY') );
                        //$response['start_time'] = ( isset( $MerchantDetail->start_time ) && !empty( $MerchantDetail->start_time ) ? $MerchantDetail->start_time : Config::get('constant.EMPTY') );
                        //$response['end_time'] = ( isset( $MerchantDetail->end_time ) && !empty( $MerchantDetail->end_time ) ? $MerchantDetail->end_time : Config::get('constant.EMPTY') );
                        $response['closed'] = ( isset( $MerchantDetail->closed ) && !empty( $MerchantDetail->closed ) ? "Holidays : ". $MerchantDetail->closed : Config::get('constant.EMPTY') );                        
                        $response['start_end_time'] = ( !empty( $MerchantDetail->start_time ) && !empty( $MerchantDetail->end_time ) ? "Open hours : ". $MerchantDetail->start_time ." - ".$MerchantDetail->end_time  : Config::get('constant.EMPTY') );
                        $response['features'] = CustomHelper::merchantFeatureDetails( $MerchantDetail->vendor_id );
                       // $response['loyaltyDetails'] = CustomHelper::merchantLoyaltyDetails( $MerchantDetail->vendor_id );
                        $response['profile_images'] = CustomHelper::merchantProfilePhotoDetails( $MerchantDetail->vendor_id );
                        $response['tag_names'] = CustomHelper::merchantTagDetails( $MerchantDetail->vendor_id );
                        $response['social_names'] = CustomHelper::merchantSocialMediaDetails( $MerchantDetail->vendor_id );
                    }

                    return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                    
                } else {
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printProfileNotCreated() ], $this->failureStatusCode );
                }

            } else { 
                    
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doMobileLogin(Request $request) {
        
        $input = $request->all();         
        $mobileNumber = $input['mobile_number'];
    
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
            //if( $merchantStatus->status_name == Config::get('constant.MERCHANT_STATUS.APPROVED') || $merchantStatus->status_name == Config::get('constant.MERCHANT_STATUS.UNAPPROVED') ) {

                $credentialDetail = new Credential();
                $mobileNoExists = $credentialDetail->checkMobileNoExists( Input::get('mobile_number') );
                
                if(!empty( $mobileNoExists) ) {

                    $merchantStatus = CustomHelper::getMerchantStatus( $mobileNumber );

                    if( $merchantStatus->status_name == Config::get('constant.MERCHANT_STATUS.BLOCKED') ) {
                        
                        return response()->json(['success' => $this->failureStatus, 'key' => Config::get('constant.TEXT.FIVE'), 'message' => $this->printMerchantLoginBlocked() ], $this->failureStatusCode );
                    }

                    if( !empty( $mobileNoExists->is_mobile_verified ) && $mobileNoExists->is_mobile_verified == Config::get('constant.NUMBER.ONE') ) {

                        $merchantDetail = new MerchantDetail();
                        $merchant = $merchantDetail->ischeckMobileverified( Input::get('mobile_number') );

                        if( !empty( $merchant->is_admin_approved ) && $merchant->is_admin_approved == Config::get('constant.NUMBER.ONE') ) { 

                            if( !empty( $merchant->is_login_approved ) && $merchant->is_login_approved == Config::get('constant.NUMBER.ONE') ) {

                                $notifiDetailSave = new Notification();
                                $notifiDetailSave->saveLoginNotification( $merchant );

                                return response()->json(['success' => $this->successStatus, 'key' => Config::get('constant.TEXT.FOUR'), 'message' => $this->printLoginSuccess() ], $this->successStatusCode );

                            } else {

                                //Send SMS Mobile verification Process
                                $this->__sendSMSMobileVerified( Input::get('mobile_number') );
                                return response()->json(['success' => $this->successStatus, 'key' => Config::get('constant.TEXT.TWO'), 'message' => $this->printAlreadyExistsUser() ], $this->successStatusCode );
                            }
        
                        } else {                    
                        
                            return response()->json(['success' => $this->failureStatus, 'key' => Config::get('constant.TEXT.THREE'), 'message' => $this->printCustomerSupport() ], $this->failureStatusCode );
                        
                        }
                    }else {        
                        
                        //Send SMS Mobile verification Process
                        $this->__sendSMSMobileVerified( Input::get('mobile_number') );
                        
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printMerchantLoginSuccess() ], $this->failureStatusCode );
                    }
                
                } else {
                        
                    //Send SMS Mobile verification Process
                    $this->__sendSMSMobileVerified( Input::get('mobile_number') );
                    
                    return response()->json(['success' => $this->successStatus, 'key' => Config::get('constant.TEXT.ONE'), 'message' => $this->printNewUser() ], $this->successStatusCode );
                } // Mobile no exists
            
           /* } else {
                
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printMerchantStatus() ], $this->failureStatusCode );
            } */  
        
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  
           
    }

    public function loginVerifyOtp(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        $otp = $input['otp'];

        if( isset( $otp ) && !empty( $otp ) ) {    

            $mobileOtp = new MobileOtp();
            $merchantMobileNoOTP = $mobileOtp->isCheckMobileNoExistsOTP( $mobileNumber, $otp );
            
            if ( isset( $merchantMobileNoOTP ) && !empty( $merchantMobileNoOTP ) )  {
                
                CustomHelper::isCheckMerchantLoginActivateUser( $mobileNumber);
                
                $merchantDetail = new MerchantDetail();
                $merchant = $merchantDetail->ischeckMobileverified( Input::get('mobile_number') );
                
                $notifiDetailSave = new Notification();
                $notifiDetailSave->saveLoginNotification( $merchant );

                return response()->json(['success' => $this->successStatus, 'message' => $this->printOTPSuccess() ], $this->successStatusCode );

            } else {
                
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPWrong() ], $this->failureStatusCode );
            }

        } else {
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPMissing() ], $this->failureStatusCode );
        }

    }


    public function logoutPassword(Request $request) {
    
        $input = $request->all();

        $mobileNumber = $input['mobile_number'];

        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {

            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
        
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $updateLoginApproved =  MerchantDetail::where( [ 'mobile_number' => $mobileNumber ] )->update( [ 'is_login_approved' => Config::get('constant.NUMBER.ZERO') ] );
                
                $response = array();
                
                if (! $updateLoginApproved ) {
                    
                    $response['message'] = $this->printLoginOutfalse();
                    $response['key'] = Config::get('constant.TEXT.NO');
                    return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );

                } else {
                    $response['message'] = $this->printLoginOutSuccess(); 
                    $response['key'] = Config::get('constant.TEXT.YES');
                    
                    $merchantDetail = new MerchantDetail();
                    $merchant = $merchantDetail->ischeckMobileverified( $mobileNumber );

                    $notifiDetailSave = new Notification();
                    $notifiDetailSave->saveLogoutNotification( $merchant );

                    return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                }
            
            }else {     

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }            

    } 
    
    public function merchantRedeemVerify(Request $request) {
        
        $input = $request->all();

        $redeemCode = $input['redeem_code'];
        $mobileNumber = $input['mobile_number'];

        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {

            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
        
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $getMerchantRedeemDetail = new RedeemCode();
                $data = $getMerchantRedeemDetail->isCheckMerchantRedeemCode( $input );

                if( isset( $data ) && !empty( $data ) ) {    
                    
                    $promotion = CustomHelper::getPromotionByRedeemCode( $redeemCode, $data['mobile_number'] );
                
                    if( isset( $promotion ) && !empty( $promotion ) ) {

                        if( $promotion->status != Config::get('constant.NUMBER.ONE') ) {
                            
                            //$getRedeemDetail = new RedeemCode();
                            //$redeemCodeDetails = $getRedeemDetail->updateRedeemCode( $input );
                            $response = array();
                            $response = CustomHelper::getUserTransactionsProcess( $redeemCode );
                            if( isset( $response ) && !empty( $response ) ) {

                                $response['message'] = $this->printRedeemClaimed();
                                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                        
                            }else {
                                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
                            }
                        
                        } else {

                            return response()->json(['success' => $this->failureStatus, 'message' => $this->printRedeemCodeAlreadyExists() ], $this->failureStatusCode );
                        }
                    } else {
                    
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printRedeemCodeInvalid() ], $this->failureStatusCode );
                    }
                } else {

                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printRedeemCodeMissing() ], $this->failureStatusCode );
                } 
            }else {     

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }             

    } 

 
    /**
     * Store a newly created transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function merchantTransaction(Request $request) {
        
        $input = $request->all();
        //echo "<pre>";
        //print_r($input); exit;
        
        $mobileNumber = $input['mobile_number'];
        $amount = $input['amount'];
    
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            $billBalanceAmt = $usrTransAmt = $usrPoint = 0;
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );

            //echo "<pre>";
            //print_r($mobileNoExists); exit;

            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
        
                $response = array();
                $getUserDetail = new RedeemCode();
                $userDetails = $getUserDetail->getUserTransactionDetails( $input );
                
                //echo "<pre>";
                //print_r($userDetails); exit;

                $merZoinPoint = $userDetails[0]->merBalance;
                $zoinPoint = $userDetails[0]->zoin_point;
                $maxBillAmt = $userDetails[0]->max_bill_amount;
                $maxCheckIn = $userDetails[0]->max_checkin;
  
                $getUserTransactions = new Transaction();
                $usrTransAmt = $getUserTransactions->getUserTransactions( $userDetails );
                
                //$loyaltyBalance = new LoyaltyBalance();
                //$usrBalanceAmt = $loyaltyBalance->checkLoyaltyBalance( $userDetails );
                $usrBalanceAmt = ( isset( $userDetails[0]->user_balance ) && !empty( $userDetails[0]->user_balance ) ? $userDetails[0]->user_balance : 0);
                if( isset( $usrBalanceAmt ) && !empty( $usrBalanceAmt ) ) {
                    $usrTransAmt = $usrTransAmt + $usrBalanceAmt; 
                }
                
                $usrBillAmt = $amount;
                if( isset( $usrTransAmt ) && !empty( $usrTransAmt ) ) {    
                    $usrBillAmt = $usrTransAmt + $amount;
                }
              
                //echo $usrBillAmt; exit;

                $zoinPoints =  $usrBillAmt / $maxBillAmt;
                $zoinPointVal = (int) $zoinPoints;
                
                //$usrPoint = $zoinPointVal * $zoinPoint;
                $usrPoint = $zoinPoint;
                
                if($merZoinPoint > 0) {	
                
                    if( $merZoinPoint > $usrPoint) {

                        $getTransactions = new Transaction();
                        $transaction_id = $getTransactions->saveNewTransaction( $input, $amount );
                        
                        $userIncrement = new RedeemCode();
                        $usrCheckIn = $userIncrement->getUserCheckInIncrement( $input );
     
                        $getTransactionRecords = new Transaction();
                        $transactionRecords = $getTransactionRecords->getTransactionDetails( $transaction_id );

                        /* echo "<pre>";
                        print_r($transactionRecords);
                        exit; */

                        $notifiDetailSave = new Notification();
                        $notifiDetailSave->saveTransactionNotification( $transactionRecords );

                        if( $usrBillAmt >= $maxBillAmt && $usrCheckIn >= $maxCheckIn ) {
                
                           // $billBalAmt =  $usrBillAmt % $maxBillAmt; //Balance Amount

                            $loyaltyUser = new LoyaltyBalance();
                            $existUser = $loyaltyUser->checkLoyaltyBalance( $userDetails ); 
                            
                            if( empty( $existUser ) ) {
                                $saveLoyaltyBalance = new LoyaltyBalance();
                                $saveLoyaltyBalance->saveUserLoyaltyBalance( $userDetails );
                            }

                            $loyaltyMerchant = new LoyaltyBalance();
                            $existMerchant = $loyaltyMerchant->checkMerchantLoyaltyBalance( $userDetails ); 
                            
                            if( empty( $existMerchant ) ) {
                                $saveLoyaltyBalance = new LoyaltyBalance();
                                $saveLoyaltyBalance->saveMerchantLoyaltyBalance( $userDetails );
                            }
                            
                            $getUsrBalance = new ZoinBalance();
                            $usrTotAmt = $getUsrBalance->getUserBalance( $userDetails );
         
                            if( isset( $usrTotAmt ) && !empty( $usrTotAmt ) ) {
                              
                                DB::table('zoin_balance')->where('vendor_or_user_id', $userDetails[0]->user_id)->increment('zoin_balance', $usrPoint);
            
                            } else {
                                
                                $userDetailSave = new ZoinBalance();  
                                $userDetailSave->vendor_or_user_id = $userDetails[0]->user_id; 
                                $userDetailSave->zoin_balance = $usrPoint;
                                $userDetailSave->save(); 
                            }

                            $billBalanceAmt =  $usrBillAmt - $maxBillAmt; //Balance Amount
                            if( isset( $billBalanceAmt ) && !empty( $billBalanceAmt ) ) {
                                //$saveUserBalance = new LoyaltyBalance();
                                //$saveUserBalance->userLoyaltyBalanceIncrement( $userDetails[0]->user_id, $billBalanceAmt );
                                $saveUserBalance = new RedeemCode();
                                $saveUserBalance->userLoyaltyBalanceIncrement( $userDetails, $billBalanceAmt );
                            } else {
                                $saveUserBalDec = new RedeemCode();
                                $saveUserBalDec->userLoyaltyBalanceDecrement( $userDetails, $billBalanceAmt );
                            }
                            
                            //Zoin Merchant Balance reduce
                            DB::table('zoin_balance')->where('vendor_or_user_id', $userDetails[0]->vendor_id)->decrement('zoin_balance', $usrPoint);

                            $notifiDetailSave = new Notification();
                            $notifiDetailSave->saveUserPointNotification( $userDetails[0]->vendor_id, $userDetails[0]->user_id, $usrPoint, $transactionRecords['transaction_id'] );

                            $notifiDetailsSave = new Notification();
                            $notifiDetailsSave->saveMerchantPointNotification( $userDetails[0]->vendor_id, $userDetails[0]->user_id, $usrPoint, $transactionRecords['transaction_id'] );

                            DB::table('transactions')->where(['vendor_id' => $userDetails[0]->vendor_id])->where(['user_id' => $userDetails[0]->user_id])->where(['loyalty_id' => $userDetails[0]->loyalty_id])->update( ['status'=> Config::get('constant.NUMBER.ONE') ] );
                            DB::table('loyalty_balance')->where('user_id', $userDetails[0]->user_id)->increment('claimed_loyalty');
                            DB::table('loyalty_balance')->where('vendor_id', $userDetails[0]->vendor_id)->increment('claimed_loyalty');

                            if( $usrCheckIn >= $maxCheckIn ) {
                                
                                $balCheckIn = $usrCheckIn - $maxCheckIn;  //Balance Checkin 
                               // DB::table('loyalty_balance')->where(['user_id' => $userDetails[0]->user_id])->update( ['total_loyalty'=> $balCheckIn ] ); 
                                DB::table('redeem_code')->where(['vendor_id' => $userDetails[0]->vendor_id])->where(['user_id' => $userDetails[0]->user_id])->where(['loyalty_id' => $userDetails[0]->loyalty_id])->update( ['user_checkin'=> $balCheckIn ] );                            
                            }	
 
                            $this->__getUserResponseDetails( $userDetails, $usrBillAmt );
                            $response['transaction_id'] = $transactionRecords['transaction_id'];
                            $response['message'] = $this->printTransanctionCompleted();
                            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
        
                        } else {
                            
                            $this->__getUserResponseDetails( $userDetails, $usrBillAmt );
                            $response['transaction_id'] = $transactionRecords['transaction_id'];
                            $response['message'] = $this->printTransanctionCompleted();
                            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
  
                        }
                        
                    } else {
                        //echo "Merchant Point is not enough";
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printMerchantPoint() ], $this->failureStatusCode );
                    }
                } else {
                    //echo "Merchant Balance is not enough";
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printMerchantBalance() ], $this->failureStatusCode );
                }


            } else { 
                    
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  
        
    }
    
    public function __getUserResponseDetails( $userDetails , $usrBillAmt) {
        //$redeemCode = CustomHelper::__getRedeemCodeGeneration();
        $redeemCode = CustomHelper::__redeemCodeGeneration( Config::get('constant.NUMBER.TWO') , Config::get('constant.NUMBER.FOUR') );   
        $redeemCodes = RedeemCode::where("vendor_id", '=', $userDetails[0]->vendor_id )
                                  ->where("user_id", '=', $userDetails[0]->user_id )
                                  ->where("loyalty_id", '=', $userDetails[0]->loyalty_id )
                                  ->where("mobile_number", '=', $userDetails[0]->mobile_number )
                                  //->where("redeem_code", '=', $userDetails[0]->redeem_code )
                                  ->select('id','redeem_code','mobile_number','vendor_id','user_id','loyalty_id','status')->first();
        $redeemCodes->status = Config::get('constant.NUMBER.ZERO');
        $redeemCodes->redeem_code = $redeemCode;
        $redeemCodes->save();
    }

    
    public function loyaltyCount(Request $request) {
        
        $input = $request->all();
        
        $loyaltyCheck = new Loyalty();
        $loyaltyCount = $loyaltyCheck->isCheckLoyaltyCount( $input['vendor_id'] );
        
        $response = array();
        if($loyaltyCount >= Config::get('constant.NUMBER.ONE') ) {
            
            $response['key'] = Config::get('constant.NUMBER.ONE');
            $response['message'] = $this->printAddMoreLoyalty();
            
            // If loyalty More than one.
            return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );
        } else {
            
            //$response['message'] = $this->printVendorMissing();
            $response['key'] = Config::get('constant.NUMBER.ZERO');
            // If loyalty save falis.
            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
 
        }          

    }


    public function MerchantTransactionLoyalty(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                
                $loyaltyDetail = new Loyalty();
                $loyaltyDetails = $loyaltyDetail->getCliamedLoyaltySubmitDetails( $vendors['vendor_id'] );        
                               
                $response = array();
                if( ! $loyaltyDetails->isEmpty() ) {
                    
                    $i = Config::get('constant.NUMBER.ZERO'); 
                    foreach($loyaltyDetails as $key => $loyaltyDetail) {

                        $response[$i]['vendor_id'] = $loyaltyDetail->vendor_id;
                        $response[$i]['company_name'] = $loyaltyDetail->company_name;
                        $response[$i]['mobile_number'] = $loyaltyDetail->mobile_number;
                        $response[$i]['max_checkin'] = $loyaltyDetail->max_checkin;
                        $response[$i]['max_bill_amount'] = $loyaltyDetail->max_bill_amount;
                        $response[$i]['loyalty_id'] = $loyaltyDetail->loyalty_id;
                        $response[$i]['loyalty_status'] = $loyaltyDetail->loyalty_status;
                       // $response[$i]['user_bill_amount'] = CustomHelper::getMerchantTransactions( $loyaltyDetail ); 

                        $j = Config::get('constant.NUMBER.ZERO');
                        $transactionDetail = new Transaction();
                        $getTransactionDetails = $transactionDetail->getMerTransLoyaltyDetails( $loyaltyDetail->vendor_id, $loyaltyDetail->loyalty_id );

                        if( ! $getTransactionDetails->isEmpty() ) {
                            foreach($getTransactionDetails as $key => $getTransactionDetail){
                                    
                                $response[$i]['redeemedList'][$j] = array(
                                    'transaction_id'=> $getTransactionDetail['transaction_id'],
                                    //'bill_amount'=> html_entity_decode(CustomHelper::get_currency_symbol('INR'), ENT_QUOTES)."".$getTransactionDetail['user_bill_amount'],
                                    'bill_amount' => ( isset( $getTransactionDetail['user_bill_amount'] ) && !empty( $getTransactionDetail['user_bill_amount'] ) ? $getTransactionDetail['user_bill_amount'] : Config::get('constant.EMPTY') ),
                                    'image' => ( isset( $getTransactionDetail['bill_path'] ) && !empty( $getTransactionDetail['bill_path'] ) ? $getTransactionDetail['bill_path'] : Config::get('constant.EMPTY') ),
                                    'redeem_status' => ( ( $getTransactionDetail['transaction_status'] == "Approved" ) ? "Success" : Config::get('constant.EMPTY') ),
                                    'date_format'=> CustomHelper::getZoinDateandTimeFormat( $getTransactionDetail['creation_date'] ),
                                );
                                $j++;
                            }
                        } /* else {
                            $response[$i]['redeemedList'][$j] = $this->printNoRecords();
                            return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );
                        } */  
                        $i++;
                    }
                    return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                } else {

                   return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyNotCreated() ], $this->failureStatusCode );
                } 

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }


    public function merchantTransactionProcess(Request $request) {
        
        $input = $request->all();
        $vendorId = $input['vendor_id'];
       
        if( isset( $vendorId ) ) {
         
            $transactionDetail = new Transaction();
            $getTransactionDetails = $transactionDetail->getMerchantTransactionsDetails( $input );
            
            /* echo "<pre>";
            print_r($getTransactionDetails);
            exit; */

            if( ! $getTransactionDetails->isEmpty() ) {
                
                $response = array();
                $i = Config::get('constant.NUMBER.ZERO');        
                foreach($getTransactionDetails as $key => $getTransactionDetail){
                    $response[$i]['transaction_id'] =  $getTransactionDetail['transaction_id'];
                    $response[$i]['user_name'] =  CustomHelper::getUserdetails( $getTransactionDetail['user_id'] );
                   // $response[$i]['user_id'] =   $getTransactionDetail['user_id'];
                    //$response[$i]['bill_amount'] = html_entity_decode(CustomHelper::get_currency_symbol('INR'), ENT_QUOTES)."".$getTransactionDetail['user_bill_amount'];
                    $response[$i]['bill_amount'] = $getTransactionDetail['user_bill_amount'];
                    $response[$i]['date_format'] = CustomHelper::getZoinDateandTimeFormat( $getTransactionDetail['creation_date'] );   
                    $response[$i]['redeem_status'] = ( ( $getTransactionDetail['transaction_status'] == "Approved" ) ? "Success" : "N/A" );
                    $i++;
                }

                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                
            } else {
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }
  
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printVendorMissing() ], $this->failureStatusCode );
        }  
        
    }   

   /* public function merchantNotification(Request $request) {
        
        $input = $request->all();
        $vendorId = $input['vendor_id'];

        $records = DB::table('user_details as u')
                    ->select("u.*","t.*")
                    ->join('transactions as t', 't.user_id', '=', 'u.user_id')
                    ->where('t.vendor_id', '=', 'ZOINV000000001')
                    ->orderBy('t.id', 'Asc')
                    ->get();

        echo "<pre>";
        print_r($records);
        exit; 
       
        if( isset( $vendorId ) ) {
         
            $notificationDetail = new Notification();
            $getnotificationDetails = $notificationDetail->getMerchantNotificationsDetails( $vendorId );
            
            //echo "<pre>";
            //print_r($getnotificationDetails); exit;
            
            if( ! $getnotificationDetails->isEmpty() ) {
                
                $response = array();
                $i = Config::get('constant.NUMBER.ZERO');        
                foreach($getnotificationDetails as $key => $getnotificationDetail){
                    $response[$i]['subject'] =  $getnotificationDetail->subject;
                    $response[$i]['subject_id'] =  $getnotificationDetail->subject_id;
                    $response[$i]['date_format'] = CustomHelper::getZoinDateandTimeFormat( $getnotificationDetail->created_at );
                    $response[$i]['message'] =  $getnotificationDetail->message;
                    $i++;
                }
    
                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                
            } else {
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }
    
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printVendorMissing() ], $this->failureStatusCode );
        }  
        
    }  */

    public function merchantNotification(Request $request) {
        
        $input = $request->all();
        $vendorId = $input['vendor_id'];
        
        if( isset( $vendorId ) ) {
            
            $notificationDetail = new Notification();
            $getnotificationDetails = $notificationDetail->getMerchantNotificationsDetails( $vendorId );

            if( ! $getnotificationDetails->isEmpty() ) {
                
                $response = array();
                $i = Config::get('constant.NUMBER.ZERO');  
                $findme = "|";      
                foreach($getnotificationDetails as $key => $getnotificationDetail){

                    $response[$i]['transaction_id'] = ( isset( $getnotificationDetail->id ) && !empty( $getnotificationDetail->id ) ? CustomHelper::__AutoIncrement( $getnotificationDetail->id ) : '' );
                    $response[$i]['user_id'] = ( isset( $getnotificationDetail->user_id ) && !empty( $getnotificationDetail->user_id ) ? $getnotificationDetail->user_id : '' );
                    $response[$i]['subject_id'] = ( isset( $getnotificationDetail->subject_id ) && !empty( $getnotificationDetail->subject_id ) ? $getnotificationDetail->subject_id : '' );
                    $response[$i]['image'] = ( isset( $getnotificationDetail->image ) && !empty( $getnotificationDetail->image ) ? $getnotificationDetail->image : '' );
                   // $response[$i]['key_image'] = "key".CustomHelper::getNotificationKeyStatus( $getnotificationDetail->image );
                   $response[$i]['message'] = ( isset( $getnotificationDetail->message ) && !empty( $getnotificationDetail->message ) ? $getnotificationDetail->message : '' );
                   if (stripos( $getnotificationDetail->message , $findme ) !== false) {
                        $data = explode("|", $getnotificationDetail->message);  
                        $response[$i]['message1'] = ( isset( $data[0] ) && !empty( $data[0] ) ? trim( $data[0] ) : '' );
                        $response[$i]['message2'] = ( isset( $data[1] ) && !empty( $data[1] ) ? trim( $data[1] ) : '' );
                    } else {
                        $response[$i]['message1'] = ( isset( $getnotificationDetail->message ) && !empty( $getnotificationDetail->message ) ? $getnotificationDetail->message : '' );
                        $response[$i]['message2'] = '';
                    }
                    $response[$i]['amount'] = ( isset( $getnotificationDetail->amount ) && !empty( $getnotificationDetail->amount ) ? $getnotificationDetail->amount : '' );
                    $response[$i]['date_format'] = ( isset( $getnotificationDetail->created_at ) && !empty( $getnotificationDetail->created_at ) ? CustomHelper::getZoinDateFormat( $getnotificationDetail->created_at ) : '' );
                    $response[$i]['time_format'] = ( isset( $getnotificationDetail->created_at ) && !empty( $getnotificationDetail->created_at ) ? CustomHelper::getZoinTimeFormat( $getnotificationDetail->created_at ) : '' );
                    
                    $i++;
                }
    
                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                
            } else {
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }

        }else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printVendorMissing() ], $this->failureStatusCode );
        } 
    }

    public function merchantCompanyDescription(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $updateDescription =  MerchantDetail::where( [ 'mobile_number' => $mobileNumber ] )->update( [ 'description' =>  $input['description'] ] );

                if (! $updateDescription ) {                   

                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printDescriptionFailure() ], $this->failureStatusCode );

                } else {
                    
                    $merchantDetail = new MerchantDetail();
                    $merchant = $merchantDetail->ischeckMobileverified( $mobileNumber );
                    $notifiDetailSave = new Notification();
                    $notifiDetailSave->saveEditProfileNotification( $merchant );
                  
                    return response()->json(['success' => $this->successStatus, 'message' => $this->printDescriptionSuccess() ], $this->successStatusCode );
                }

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }

    public function merchantTags(Request $request) {
        
        $input = $request->all();
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $merchant = $merchantDetail->ischeckMobileverified( Input::get('mobile_number') );

                $getTagMerchant = new TagMerchants();
                $tagCount = $getTagMerchant->getTagCount( $merchant['vendor_id'] );

                if( $tagCount >= Config::get('constant.NUMBER.FOUR') ) {
                    // If Tag More than Four.
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printAddMoreTag() ], $this->failureStatusCode );
                } else {
                    CustomHelper::checkMerchantTags( $input['tag_name'] ,$merchant['vendor_id'] );
                    $notifiDetailSave = new Notification();
                    $notifiDetailSave->saveEditProfileTagNotification( $merchant );
                    return response()->json(['success' => $this->successStatus, 'message' => $this->printTagCreated() ], $this->successStatusCode );
                }

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }

    public function merchantTagDelete(Request $request) {
        
        $input = $request->all();
        $vendorId = $input['vendor_id'];
        $tag_id = $input['tag_id'];
        
        if( isset( $vendorId ) ) {
          
           $getTagMerchant = new TagMerchants();
           $tags = $getTagMerchant->getTagMerchant( $vendorId, $tag_id );
          
           if( isset( $tags ) && !empty( $tags ) ) {
                
                //$getTagMerchant = new TagMerchants();
                //$getTagMerchant->removeSelectTags( $vendorId, $tag_id );

                // Already Tags - delete the existing
                $tags->delete();
                
                return response()->json(['success' => $this->successStatus, 'message' => $this->printMerchantTagRemove() ], $this->successStatusCode );
                
            } else {
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }

        }else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printVendorMissing() ], $this->failureStatusCode );
        } 
    }

    public function merchantLoyaltyStatus(Request $request) {
        
        $input = $request->all();
        $loyaltyId = $input['loyalty_id'];
        $status = $input['status'];
       
        if( isset( $loyaltyId ) && !empty( $loyaltyId ) ) {    
            
            $loyaltyDetail = new Loyalty();
            $getLoyaltyDetails = $loyaltyDetail->getMerchantStatusLoyaltyDetails( $loyaltyId );
            
            if( isset( $getLoyaltyDetails ) && !empty( $getLoyaltyDetails ) ) {
                
                DB::table('loyalty')->where("loyalty_id", '=', $loyaltyId)->update(array('loyalty_status' => $status)); 
                
                $notifiDetailSave = new Notification();
                $notifiDetailSave->saveLoyaltyActiveNotification( $getLoyaltyDetails );
                
                return response()->json(['success' => $this->successStatus, 'message' => $this->printMerchantStatusUpdate() ], $this->successStatusCode );
                
            } else {
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }
  
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyMissing() ], $this->failureStatusCode );
        }  
        
    }

    /* public function appVersion() {
         
        $results = DB::select('select * from version');
        $response = array();
        if( isset( $results ) && !empty( $results ) ) {
            
            $response['version'] = $results[0]->version_name;
            $response['date_format'] = ( isset( $results[0]->created_at ) && !empty( $results[0]->created_at ) ? CustomHelper::getZoinDateFormat( $results[0]->created_at ) : '' );
            $response['time_format'] = ( isset( $results[0]->created_at ) && !empty( $results[0]->created_at ) ? CustomHelper::getZoinTimeFormat( $results[0]->created_at ) : '' ); 
            $response['message'] = $this->printVersionUpdate();
            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
            
        } else {
            
            $response['message'] = $this->printNoRecords();            
            return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );

        }          

    } */

    public function LoyaltyPopupMenu(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                
                $loyaltyDetail = new Loyalty();
                $loyaltyDetails = $loyaltyDetail->getLoyaltyDetails( $vendors['vendor_id'] );        
                
                if( ! $loyaltyDetails->isEmpty() ) {
                    
                        $loyaltySubmitDetail = new Loyalty();
                        $loyaltySubmitDetails = $loyaltySubmitDetail->getloyaltyPopupDetail( $vendors['vendor_id'] ); 
              
                        if(count($loyaltySubmitDetails) < 2) {
                            
                            $response = array();
                            foreach($loyaltySubmitDetails as $key => $loyaltySubmitDetail){
                                
                                $response['message'] = ( isset( $loyaltySubmitDetail['loyalty_status'] ) && !empty( $loyaltySubmitDetail['loyalty_status'] ) ? CustomHelper::getPopupMenuContent( $loyaltySubmitDetail['loyalty_status'] ) : Config::get('constant.EMPTY') );
                                $response['key'] =  CustomHelper::getPopupStatusBasedKey( $loyaltySubmitDetail['loyalty_status'] );
                            }

                            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                        } else {

                            return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
                        }
                    
                } else {
                    $response = array();
                    $response['message'] = Config::get('constant.NOTIFICATION.POPUP.ADD_LOYALTY');
                    $response['key'] = Config::get('constant.TEXT.ONE');
                    return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode ); 
                }                

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }

    public function merchantSocialMedia(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        $name = $input['name'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );
                 
                $socialMediaDetail = new MerchantSocialMedia();
                $records = $socialMediaDetail->getSocialMediaDetails( $vendors['vendor_id'], $name );
                
                $i = 0;
                foreach($records as $key => $record) {
                    $data[$i] = $record['social_name'];
                    $i++;
                }

                return response()->json(['success' => $this->successStatus, 'message' => $records], $this->successStatusCode );  

            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }


    public function merchantTagList(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
               
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantDetail = new MerchantDetail();
                $vendors = $merchantDetail->getMobileNoBasedVendorId( $mobileNumber );

                $getMerchantTags = new MerchantTags();
                $tags = $getMerchantTags->getMerchantTagLists( $vendors['vendor_id'] );

                if( ! $tags->isEmpty() ) {
                    
                    $tagResponse = array();
                    $tagResponse["tag_id_1"] = ( isset( $tags[0] ) && !empty( $tags[0] ) ? $tags[0]->id : '' );
                    $tagResponse["tag_name_1"] = ( isset( $tags[0] ) && !empty( $tags[0] ) ? $tags[0]->tag_name : '' );
                    $tagResponse["tag_id_2"] = ( isset( $tags[1] ) && !empty( $tags[1] ) ? $tags[1]->id : '' );
                    $tagResponse["tag_name_2"] = ( isset( $tags[1] ) && !empty( $tags[1] ) ? $tags[1]->tag_name : '' );
                    $tagResponse["tag_id_3"] = ( isset( $tags[2] ) && !empty( $tags[2] ) ? $tags[2]->id : '' );
                    $tagResponse["tag_name_3"] = ( isset( $tags[2] ) && !empty( $tags[2] ) ? $tags[2]->tag_name : '' );
                    $tagResponse["tag_id_4"] = ( isset( $tags[3] ) && !empty( $tags[3] ) ? $tags[3]->id : '' );
                    $tagResponse["tag_name_4"] = ( isset( $tags[3] ) && !empty( $tags[3] ) ? $tags[3]->tag_name : '' );
                   
                    return response()->json(['success' => $this->successStatus, 'message' => $tagResponse], $this->successStatusCode );
                    
                } else {
                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
                } 
                
            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }


    public function appVersion(Request $request) {
        
        $input = $request->all();        
        $version = $input['version'];
               
        if( isset( $version ) && !empty( $version ) ) {
            
            $records = DB::table('version')->where("version_name", '=', $version)->first();
            
            $response = array();
            if( isset( $records ) && !empty( $records ) ) {
                
                $response['version'] = $records->version_name;
                $response['date_format'] = ( isset( $records->created_at ) && !empty( $records->created_at ) ? CustomHelper::getZoinDateFormat( $records->created_at ) : '' );
                $response['time_format'] = ( isset( $records->created_at ) && !empty( $records->created_at ) ? CustomHelper::getZoinTimeFormat( $records->created_at ) : '' ); 
                $response['message'] = $this->printVersionUpdate();
                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                
            } else {
                
                $response['message'] = $this->printNoRecords();            
                return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );

            }   

        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printVesrionMissing() ], $this->failureStatusCode );
        }     
    
    }
    

    public function merchantBlock(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $merchantStatus = CustomHelper::getMerchantStatus( $mobileNumber );
                $response = array();
                if( $merchantStatus->status_name == Config::get('constant.MERCHANT_STATUS.BLOCKED') ) {
                    $response['message'] = $this->printMerchantBlocked(); 
                    $response['key'] = Config::get('constant.TEXT.YES');
                    return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                }else {
                    $response['message'] = $this->printMerchantUnBlocked();
                    $response['key'] = Config::get('constant.TEXT.NO');
                    return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );
                }
            } else { 
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }    
        
        } else {
            
           return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }     
    
    }

}
