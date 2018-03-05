<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Helpers\CustomHelper;
use App\Traits\UserMessage;
use App\Traits\CustomMessage;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\UserDetail;
use App\MerchantDetail;
use App\Address;
use App\Credential;
use App\MobileOtp;
use App\ForgotOtp;
use App\BusinessRule;
use App\MerchantImage;
use App\CheckinLimit;
use App\BusinessType;
use App\Transaction;
use App\UserMobileOtp;
use App\Notification;
use App\RedeemCode;
use App\Loyalty;
use Config;
use Mail;
use DB;

class UserApiController extends Controller {
    
    use UserMessage, CustomMessage;
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
     public function userRegistration(Request $request) {
        
        $input = $request->all();
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($input, UserDetail::$regisUserRules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
        
            $errors = $validator->messages()->toArray();
            $message = CustomHelper::checkEmailMobileNoExists($errors);
            
            // If validation falis redirect back to login.
            return response()->json(['success' => $this->failureStatus, 'message' => $message], $this->failureStatusCode );
            
        } else {
            
            //$addressDetailSave = new Address();
            //$addressId = $addressDetailSave->saveUserAddress( $input );

            $UserDetailSave = new UserDetail();
            $UserId = $UserDetailSave->saveUsers( $input );

            if( isset( $UserId ) && !empty( $UserId ) ) {  
                
                $response = array();
                $userDetail = new UserDetail();
                $userDetails = $userDetail->checkUserMobileNoExists( $input['mobile_number'] );
                $response['user_id'] = $userDetails['user_id'];

                $userNotiDetail = new UserDetail();
                $mobileNoExists = $userNotiDetail->checkUserMobileNoExists( $input['mobile_number'] );
                
                $notifiDetailSave = new Notification();
                $notifiDetailSave->saveUserLoginNotification( $mobileNoExists );

                $response['message'] = $this->printUserRegisterSuccess();

                // If User save Success.
                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );	    

            } else {

                // If User save falis.
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printUserRegisterFalse() ], $this->failureStatusCode );

            } 
        
        } 

    }

    public function __sendSMSMobileVerified( $mobileNumber ) {
        
        // $generateOTPToken
        $otpGenerate = CustomHelper::__otpGeneration( $mobileNumber );                
        // send sms
        CustomHelper::sendSms( $mobileNumber, $otpGenerate );
        //Exists OTP Remove 
        CustomHelper::isUserExistOTPRemove( $mobileNumber, $otpGenerate );

        return $otpGenerate;
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
            
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
            
            //echo "<pre>";
            //print_r($mobileNoExists); exit;

            if(!empty( $mobileNoExists) ) {
                $response = array();    
                if( !empty( $mobileNoExists->is_mobile_verified ) && $mobileNoExists->is_mobile_verified == Config::get('constant.NUMBER.ONE') ) {
                    
                    if( !empty( $mobileNoExists->is_login_approved ) && $mobileNoExists->is_login_approved == Config::get('constant.NUMBER.ONE') ) {
                        
                        $response['user_id'] =  $mobileNoExists['user_id']; 
                        $response['message'] =  $this->printLoginSuccess(); 
                        
                        $notifiDetailSave = new Notification();
                        $notifiDetailSave->saveUserLoginNotification( $mobileNoExists );
                        
                        return response()->json(['success' => $this->successStatus, 'key' => Config::get('constant.TEXT.FOUR'), 'message' => $response ], $this->successStatusCode );
               
                    } else {

                        //Send SMS Mobile verification Process
                        $this->__sendSMSMobileVerified( Input::get('mobile_number') );
                        $response['user_id'] =  $mobileNoExists['user_id']; 
                        $response['message'] =  $this->printAlreadyExistsUser();
                        return response()->json(['success' => $this->successStatus, 'key' => Config::get('constant.TEXT.TWO'), 'message' => $response ], $this->successStatusCode );
                    }    
       
                }else {        
                    
                    //Send SMS Mobile verification Process
                    $this->__sendSMSMobileVerified( Input::get('mobile_number') );
                    
                    return response()->json(['success' => $this->failureStatus, 'key' => Config::get('constant.TEXT.THREE'), 'message' => $this->printMobileNoNotVerified() ], $this->failureStatusCode );
                }
            
            } else {
                    
                //Send SMS Mobile verification Process
                $this->__sendSMSMobileVerified( Input::get('mobile_number') );
            
                return response()->json(['success' => $this->successStatus, 'key' => Config::get('constant.TEXT.ONE'), 'message' => $this->printNewUser() ], $this->successStatusCode );
            } // Mobile no exists
        
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  
           
    }

    public function userLoginVerifyOtp(Request $request) {
        
        $input = $request->all();
         
        $mobileNumber = $input['mobile_number'];
        $otp = $input['otp'];

        if( isset( $otp ) && !empty( $otp ) ) {    

            $mobileOtp = new UserMobileOtp();
            $merchantMobileNoOTP = $mobileOtp->isCheckMobileNoExistsOTP( $mobileNumber, $otp );
            
            if ( isset( $merchantMobileNoOTP ) && !empty( $merchantMobileNoOTP ) )  {
                
                CustomHelper::activateUserStatus( $mobileNumber );

                $userDetail = new UserDetail();
                $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
                
                $notifiDetailSave = new Notification();
                $notifiDetailSave->saveUserLoginNotification( $mobileNoExists );

                return response()->json(['success' => $this->successStatus, 'message' => $this->printOTPSuccess() ], $this->successStatusCode );

            } else {
                
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPWrong() ], $this->failureStatusCode );
            }

        } else {
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPMissing() ], $this->failureStatusCode );
        }

    }


    public function userVerifyOtp(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
        $otp = $input['otp'];

        if( isset( $otp ) && !empty( $otp ) ) {    

            $mobileOtp = new UserMobileOtp();
            $merchantMobileNoOTP = $mobileOtp->isCheckMobileNoExistsOTP( $mobileNumber, $otp );
            
            if ( isset( $merchantMobileNoOTP ) && !empty( $merchantMobileNoOTP ) )  {
                
                CustomHelper::isCheckUserLoginActivateUser( $mobileNumber );

                return response()->json(['success' => $this->successStatus, 'message' => $this->printOTPSuccess() ], $this->successStatusCode );

            } else {
                
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPWrong() ], $this->failureStatusCode );
            }

        } else {
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printOTPMissing() ], $this->failureStatusCode );
        }

    }

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
     public function userSideBarList(Request $request) {
        
        $input = $request->all();
 
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
            
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $getUserDetail = new UserDetail();
                $sideBarLists = $getUserDetail->getSidebarProfileDetails( $input );

                /* echo "<pre>";
                print_r($sideBarLists);
                exit; */ 
                if( isset( $sideBarLists ) && !empty( $sideBarLists ) ) {
                    
                    $response = array();        
                    foreach( $sideBarLists as $i => $sideBarList ) { 
                        
                        $response['user_id'] =  $sideBarLists->user_id;
                        $response['full_name'] =  CustomHelper::getCamelCase($sideBarLists->full_name);
                        $response['first_letter'] =  CustomHelper::getFirstLetterReturn($sideBarLists->full_name);
                        $response['user_level'] = "Level : ".$sideBarLists->user_level;
                        $response['user_type'] = Config::get('constant.USER');
                        
                    } 

                    if( isset( $response ) && !empty( $response ) ) {    
                        
                        return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                        
                    } else {
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printProfileNotCreated() ], $this->failureStatusCode );
                    }
                    
                } else { // if sideBarLists

                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printProfileNotCreated() ], $this->failureStatusCode );
                }
            
            } else { 

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            } 

        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        } 

    }

    
    public function userProfileList(Request $request) {
        
        $input = $request->all();
        $mobileNumber = $input['mobile_number'];
    
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
        
                $getUserDetail = new UserDetail();
                $sideBarLists = $getUserDetail->getProfileDetails( $input );

                $userRedeemCode = new RedeemCode();
                $redeemCount = $userRedeemCode->userRedeemCodeCount( $mobileNumber );
  
                if( isset( $sideBarLists ) && !empty( $sideBarLists ) ) { 

                    $response = array();        
                    foreach( $sideBarLists as $i => $sideBarList ) { 
                        
                        $response['user_id'] =  $sideBarLists->user_id;
                        $response['full_name'] =  CustomHelper::getCamelCase($sideBarLists->full_name);
                        $response['first_letter'] =  CustomHelper::getFirstLetterReturn($sideBarLists->full_name);
                        $response['user_type'] = Config::get('constant.USER');
                        $response['user_level'] = ( isset( $sideBarLists->user_level ) && !empty( $sideBarLists->user_level ) ? $sideBarLists->user_level : 0);
                        $response['zoin_balance'] = ( isset( $sideBarLists->zoin_balance ) && !empty( $sideBarLists->zoin_balance ) ? $sideBarLists->zoin_balance : 0);
                        $response['city'] = ( isset( $sideBarLists->city ) && !empty( $sideBarLists->city ) ? $sideBarLists->city : ' ');
                        $response['loyalty_claimed'] = ( isset( $redeemCount ) && !empty( $redeemCount ) ? $redeemCount : Config::get('constant.NUMBER.ZERO')); 
                    } 
                    
                    if( isset( $response ) && !empty( $response ) ) {    
                        
                        return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );
                        
                    } else {
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printProfileNotCreated() ], $this->failureStatusCode );
                    }
               
                } else { // if sideBarLists

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
     public function userEditProfile(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
       
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
            
            /* echo "<pre>";
            print_r($mobileNoExists);
            exit; */ 

            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
                
                $getUpdateDetail = new UserDetail();
                $addressId = $getUpdateDetail->updateUsers( $input );
                
                if( isset( $addressId ) && !empty( $addressId ) ) {
                    $addressDetailSave = new Address();
                    $addressId = $addressDetailSave->updateAddress( $input, $addressId );

                } else {
                    $addressDetailSave = new Address();  
                    $address_id = $addressDetailSave->saveUserAddress( $input );
                    DB::table('user_details')->where('mobile_number', $mobileNumber)->update( ['address_id'=>$address_id] );
                }

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
     public function userEditProfileList(Request $request) {
        
        $input = $request->all();
        
        $mobileNumber = $input['mobile_number'];
    
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );

            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
        
                $getUserDetail = new UserDetail();
                $UserDetails = $getUserDetail->getEditProfileDetails( $input );
               
                if( isset( $UserDetails ) && !empty( $UserDetails ) ) {    
                    
                    return response()->json(['success' => $this->successStatus, 'message' => $UserDetails ], $this->successStatusCode );
                    
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


    /* public function userLoyaltyDetailList(Request $request) {
        
        $input = $request->all();
       
        $transactions = new Transaction();
        $loyaltyId = $transactions->saveNewloyaltyDetail( $input );
    
    } */
    

    /**
     * get User Explore List.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userExploreList(){
        
        $loyaltyDetail = new Loyalty();
        $loyaltyDetails = $loyaltyDetail->getAllLoyaltySubmitDetails( ); 
        
        /* echo "<pre>";
        print_r($loyaltyDetails);
        exit; */

        if( ! $loyaltyDetails->isEmpty() ) {
           
            $response = array();
            $i = Config::get('constant.NUMBER.ZERO');
            foreach($loyaltyDetails as $key => $loyaltyDetail){
               
                $response[$i]['vendor_id'] = ( isset( $loyaltyDetail->vendor_id ) && !empty( $loyaltyDetail->vendor_id ) ? $loyaltyDetail->vendor_id : Config::get('constant.EMPTY'));
                $response[$i]['company_name'] = ( isset( $loyaltyDetail->company_name ) && !empty( $loyaltyDetail->company_name ) ? $loyaltyDetail->company_name : Config::get('constant.EMPTY'));
                $response[$i]['max_checkin'] = ( isset( $loyaltyDetail->max_checkin ) && !empty( $loyaltyDetail->max_checkin ) ? $loyaltyDetail->max_checkin : Config::get('constant.EMPTY'));
                $response[$i]['max_bill_amount'] = ( isset( $loyaltyDetail->max_bill_amount ) && !empty( $loyaltyDetail->max_bill_amount ) ? $loyaltyDetail->max_bill_amount : Config::get('constant.EMPTY'));
                $response[$i]['address'] = ( isset( $loyaltyDetail->address ) && !empty( $loyaltyDetail->address ) ? $loyaltyDetail->address : Config::get('constant.EMPTY'));
                $response[$i]['loyalty_id'] = ( isset( $loyaltyDetail->loyalty_id ) && !empty( $loyaltyDetail->loyalty_id ) ? $loyaltyDetail->loyalty_id : Config::get('constant.EMPTY'));
                $response[$i]['mobile_number'] = ( isset( $loyaltyDetail->mobile_number ) && !empty( $loyaltyDetail->mobile_number ) ? $loyaltyDetail->mobile_number : Config::get('constant.EMPTY'));
                $response[$i]['zoin_point'] = ( isset( $loyaltyDetail->zoin_point ) && !empty( $loyaltyDetail->zoin_point ) ? $loyaltyDetail->zoin_point : Config::get('constant.EMPTY'));
                $response[$i]['city'] = ( isset( $loyaltyDetail->city ) && !empty( $loyaltyDetail->city ) ? $loyaltyDetail->city : Config::get('constant.EMPTY'));
                $response[$i]['contact_person'] = ( isset( $loyaltyDetail->contact_person ) && !empty( $loyaltyDetail->contact_person ) ? $loyaltyDetail->contact_person : Config::get('constant.EMPTY'));
                $response[$i]['description'] = ( isset( $loyaltyDetail->description ) && !empty( $loyaltyDetail->description ) ? $loyaltyDetail->description : Config::get('constant.EMPTY'));
                $response[$i]['ldescription'] = ( isset( $loyaltyDetail->ldescription ) && !empty( $loyaltyDetail->ldescription ) ? $loyaltyDetail->ldescription : Config::get('constant.EMPTY'));
                
                //$response[$i]['zoin_point'] = $loyaltyDetail->zoin_point;
                //$response[$i]['city'] = $loyaltyDetail->city;
                //$response[$i]['contact_person'] = $loyaltyDetail->contact_person;
                //$response[$i]['loyalty_id'] = $loyaltyDetail->loyalty_id;
                //$response[$i]['mobile_number'] = $loyaltyDetail->mobile_number;
                
               /* $getProfileImage = new MerchantImage();
                $images = $getProfileImage->getProfileImageDetails( $loyaltyDetail->vendor_id );
                $response[$i]['image'] = ( isset( $images['profile_image'] ) && !empty( $images['profile_image'] ) ? $images['profile_image'] : asset('/assets/images/zoin_empty.png'));
                $response[$i]['description'] = ( isset( $loyaltyDetail->description ) && !empty( $loyaltyDetail->description ) ? $loyaltyDetail->description : Config::get('constant.EMPTY'));
                $response[$i]['website'] = ( isset( $loyaltyDetail->website ) && !empty( $loyaltyDetail->website ) ? $loyaltyDetail->website : Config::get('constant.EMPTY'));
                //$response[$i]['start_time'] = ( isset( $loyaltyDetail->start_time ) && !empty( $loyaltyDetail->start_time ) ? $loyaltyDetail->start_time : 'null');
                //$response[$i]['end_time'] = ( isset( $loyaltyDetail->end_time ) && !empty( $loyaltyDetail->end_time ) ? $loyaltyDetail->end_time : 'null');
                $response[$i]['start_end_time'] = "Open hours : ".( isset( $loyaltyDetail->start_time ) && !empty( $loyaltyDetail->start_time ) ? $loyaltyDetail->start_time : Config::get('constant.EMPTY')) ." - ". ( isset( $loyaltyDetail->end_time ) && !empty( $loyaltyDetail->end_time ) ? $loyaltyDetail->end_time : Config::get('constant.EMPTY')); 
                $response[$i]['closed'] = ( isset( $MerchantDetail->closed ) && !empty( $MerchantDetail->closed ) ? "Holidays : ". $MerchantDetail->closed : Config::get('constant.EMPTY') );
                $response[$i]['tag_names'] = CustomHelper::merchantTagDetails( $loyaltyDetail->vendor_id );
                $response[$i]['social_names'] = CustomHelper::merchantSocialMediaDetails( $loyaltyDetail->vendor_id ); */
               
               $i++;
           }

           return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode ); 

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
        }
            
    }  //User Explore List


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userProfileExploreList(Request $request) {
     
        $input = $request->all();
       
        $mobileNumber = $input['mobile_number'];
        
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
          
            $mobileNoExists = CustomHelper::isCheckMobileNoExists( $mobileNumber );
 
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {
        
                $MerchantDetailSave = new MerchantDetail();
                $MerchantDetails = $MerchantDetailSave->getEditProfileDetails( $input );
                
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
                        $response['mobile_number'] = ( isset( $MerchantDetail->mobile_number ) && !empty( $MerchantDetail->mobile_number ) ? $MerchantDetail->mobile_number : Config::get('constant.EMPTY') );
                        $response['address'] = ( isset( $MerchantDetail->address ) && !empty( $MerchantDetail->address ) ? $MerchantDetail->address : Config::get('constant.EMPTY') );
                        $response['latitude'] = ( isset( $MerchantDetail->latitude ) && !empty( $MerchantDetail->latitude ) ? $MerchantDetail->latitude : Config::get('constant.EMPTY') );
                        $response['longitude'] = ( isset( $MerchantDetail->longitude ) && !empty( $MerchantDetail->longitude ) ? $MerchantDetail->longitude : Config::get('constant.EMPTY') );                        
                        $response['city'] = ( isset( $MerchantDetail->city ) && !empty( $MerchantDetail->city ) ? $MerchantDetail->city : Config::get('constant.EMPTY') );
                        $response['description'] = ( isset( $MerchantDetail->description ) && !empty( $MerchantDetail->description ) ? $MerchantDetail->description : Config::get('constant.EMPTY') );
                        $response['website'] = ( isset( $MerchantDetail->website ) && !empty( $MerchantDetail->website ) ? "Website : ".$MerchantDetail->website : Config::get('constant.EMPTY') );
                        $response['closed'] = ( isset( $MerchantDetail->closed ) && !empty( $MerchantDetail->closed ) ? "Holidays : ". $MerchantDetail->closed : Config::get('constant.EMPTY') );                        
                        $response['start_end_time'] = ( !empty( $MerchantDetail->start_time ) && !empty( $MerchantDetail->end_time ) ? "Open hours : ". $MerchantDetail->start_time ." - ".$MerchantDetail->end_time  : Config::get('constant.EMPTY') );
                        $response['features'] = CustomHelper::merchantFeatureDetails( $MerchantDetail->vendor_id );
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


    public function userLogoutPassword(Request $request) {
        
        $input = $request->all();

        $mobileNumber = $input['mobile_number'];

        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {

            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
        
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $updateLoginApproved =  UserDetail::where( [ 'mobile_number' => $mobileNumber ] )->update( [ 'is_login_approved' => Config::get('constant.NUMBER.ZERO') ] );
                
                $response = array();
                
                if (! $updateLoginApproved ) {
                    
                    $response['message'] = $this->printLoginOutfalse();
                    $response['key'] = Config::get('constant.TEXT.NO');
                    return response()->json(['success' => $this->failureStatus, 'message' => $response ], $this->failureStatusCode );

                } else {
                    $response['message'] = $this->printLoginOutSuccess(); 
                    $response['key'] = Config::get('constant.TEXT.YES');

                    $notifiDetailSave = new Notification();
                    $notifiDetailSave->saveUserLogoutNotification( $mobileNoExists );

                    return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                }
            
            }else {     

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
     public function userExploreImageList(Request $request) {
        
        $input = $request->all();
        $vendorId = $input['vendor_id'];
 
        if( isset( $vendorId ) && !empty( $vendorId ) ) {    
           
            $getMerchantProfileImage = new MerchantImage();
            $profileImages = $getMerchantProfileImage->getMerchantProfileImageDetails( $vendorId );
            
            if( ! $profileImages->isEmpty() ) {           

                $j = Config::get('constant.NUMBER.ZERO');                
                $response = array();
                foreach($profileImages as $key => $profileImage) {                       
                    $response['profile_images'][$j] =  $profileImage['profile_image'];
                    $j++;
                } 
 
                return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode ); 
            
            } else {

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printVendorMissing() ], $this->failureStatusCode );
        }          

    }

    public function userExploreConfirm(Request $request) {
        
        $input = $request->all();

        $mobileNumber = $input['mobile_number'];
       
        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {
            
            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
            
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $loyaltyCombination = new Loyalty();
                $loyaltyCombinationExists = $loyaltyCombination->checkLoyaltyCombination( $input );

                /* echo "<pre>";
            print_r($loyaltyCombinationExists);
            exit; */

                if( $loyaltyCombinationExists == Config::get('constant.NUMBER.ONE') ) {

                    $redeemLoyaltyCombination = new RedeemCode();
                    $redeemCount = $redeemLoyaltyCombination->isCheckRedeemLoyaltyCount( $input );
                    
                   /*  echo "<pre>";
                    print_r($redeemCount);
                    exit; */
                    
                    if( $redeemCount >= Config::get('constant.NUMBER.ONE') ) {
                        
                        // If loyalty More than one.
                        return response()->json(['success' => $this->failureStatus, 'message' => $this->printAddMoreRedeem() ], $this->failureStatusCode );

                    } else {
                        
                        $getMerDetails = new MerchantDetail();
                        $data = $getMerDetails->getMerchantDetails( $input['vendor_id'] );
                        
                        $response = array();   
                        $redeemCode = $this->__generateRedemCode( );
                        $RedeemDetailSave = new RedeemCode();
                        $RedeemDetails = $RedeemDetailSave->saveRedeemDetails( $input , $redeemCode, $data['mobile_number'] );
                        
                        if( !isset( $RedeemDetails ) && empty( $RedeemDetails ) ) {                      
                            return response()->json(['success' => $this->failureStatus, 'message' => $this->printRedeemNotCreated()], $this->failureStatusCode );
                        } else {

                            $response['mobile_number'] = $mobileNumber;
                            $response['redeem_code'] = $redeemCode;
                            $response['vendor_id'] = $input['vendor_id'];
                        // $response['max_checkin'] = $loyaltyDetails[0]->max_checkin;
                        //  $response['max_bill_amount'] = $loyaltyDetails[0]->max_bill_amount;
                        //  $response['loyalty_id'] = $loyaltyDetails[0]->loyalty_id;                         
                            $response['message'] = $this->printRedeemCreated( );

                            $notifiDetailSave = new Notification();
                            $notifiDetailSave->saveRedeemedNotificationDetail( $input , $redeemCode );

                            return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                        }   
                    }
                    
                } else {

                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printLoyaltyCombination() ], $this->failureStatusCode );
                }
              } else { 
  
                  return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
              }    
          
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }  

    }

    public function __generateRedemCode( ) {
        
        // $generateRandomToken
        $redeemCode = CustomHelper::__redeemCodeGeneration( Config::get('constant.NUMBER.TWO') , Config::get('constant.NUMBER.FOUR') );
        //$redeemCode = CustomHelper::__getRedeemCodeGeneration( );                
 
        return $redeemCode;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function userRedeemList(Request $request) {
        
        $input = $request->all();
        $userId = $input['user_id'];
         
        if( isset( $userId ) && !empty( $userId ) ) {    
           
            $response = array();
            $getRedeemDetail = new RedeemCode();
            $redeemCodeDetails = $getRedeemDetail->getUserRedeemDetails( $userId );
           
            if( ! $redeemCodeDetails->isEmpty() ) {
                
                 $response = array();
                 $i = Config::get('constant.NUMBER.ZERO');
                 foreach($redeemCodeDetails as $key => $redeemCodeDetail){
                     
                     $response[$i]['vendor_id'] = $redeemCodeDetail->vendor_id;
                     $response[$i]['company_name'] = $redeemCodeDetail->company_name;   
                     $response[$i]['max_checkin'] = $redeemCodeDetail->max_checkin;
                     $response[$i]['max_bill_amount'] = $redeemCodeDetail->max_bill_amount;
                     $response[$i]['zoin_point'] = $redeemCodeDetail->zoin_point;
                     $response[$i]['redeem_code'] = $redeemCodeDetail->redeem_code;
                     $response[$i]['description'] = ( isset( $redeemCodeDetail->description ) && !empty( $redeemCodeDetail->description ) ? $redeemCodeDetail->description : ' ');
                    // $response[$i]['features'] = CustomHelper::merchantFeatureDetails( $redeemCodeDetail->vendor_id );
                     $i++;
                }               

                return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode ); 
            
            } else {

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printUserIDMissing() ], $this->failureStatusCode );
        }          

    }

    public function userRedeemedList(Request $request) {
        
        $input = $request->all();

        $mobileNumber = $input['mobile_number'];

        if( isset( $mobileNumber ) && !empty( $mobileNumber ) ) {

            $userDetail = new UserDetail();
            $mobileNoExists = $userDetail->checkUserMobileNoExists( $mobileNumber );
        
            if( isset( $mobileNoExists ) && !empty( $mobileNoExists ) ) {

                $redeemCodeFind = new redeemCode();
                $redeemedDetails = $redeemCodeFind->getUserRedeemedDetails( $mobileNumber ); 
               
                $response = array();
                if( ! $redeemedDetails->isEmpty() ) {                    
                    $i = Config::get('constant.NUMBER.ZERO'); 
                    foreach($redeemedDetails as $key => $redeemedDetail) {
                        
                        $response[$i]['company_name'] = $redeemedDetail->company_name;
                        $response[$i]['mobile_number'] = $redeemedDetail->mobile_number;
                        $response[$i]['max_checkin'] = $redeemedDetail->max_checkin;
                        $response[$i]['max_bill_amount'] = $redeemedDetail->max_bill_amount;
                        $response[$i]['vendor_id'] = $redeemedDetail->vendor_id;
                        $response[$i]['user_id'] = $redeemedDetail->user_id;
                        $response[$i]['loyalty_id'] = $redeemedDetail->loyalty_id;
                        $response[$i]['user_bill_amount'] = CustomHelper::getUserAllTransactionsProcess( $redeemedDetail );    
                        $response[$i]['user_checkin'] = CustomHelper::getUserRedeemedCheckIns( $redeemedDetail ); 
                        
                        $j = Config::get('constant.NUMBER.ZERO'); 
                        $transactionDetail = new Transaction();
                        $getTransactionDetails = $transactionDetail->getTransactionsDetails( $redeemedDetail->vendor_id, $redeemedDetail->user_id, $redeemedDetail->loyalty_id ); 	
                        $k = Config::get('constant.NUMBER.ZERO');
                        if( ! $getTransactionDetails->isEmpty() ) {

                            foreach($getTransactionDetails as $key => $getTransactionDetail){
                                 
                                $response[$i]['redeemedList'][$j] = array(
                                    'transaction_id'=> $getTransactionDetail['transaction_id'],
                                    //'bill_amount'=> html_entity_decode(CustomHelper::get_currency_symbol('INR'), ENT_QUOTES)."".$getTransactionDetail['user_bill_amount'],
                                    'bill_amount'=> $getTransactionDetail['user_bill_amount'],
                                    'image' => ( isset( $getTransactionDetail['bill_path'] ) && !empty( $getTransactionDetail['bill_path'] ) ? $getTransactionDetail['bill_path'] : ' '),
                                    'redeem_status' => ( ( $getTransactionDetail['transaction_status'] == "Approved" ) ? "Success" : "N/A" ),
                                    'date_format'=> CustomHelper::getZoinDateandTimeFormat( $getTransactionDetail['creation_date'] ),
                                );

                                $k++;  
                                $j++;
                            }
                        }
                        
                        if( $k == Config::get('constant.NUMBER.ZERO')){
                            $data = array();
                            $data['success'] = false;
                            $data['message'] = $this->printNoRecords();
                            $data['status'] = 400;
                            $response[$i]['redeemedList'][$j] = $data;
                        }

                        $i++;
                    }
                    return response()->json(['success' => $this->successStatus, 'message' => $response], $this->successStatusCode );  
                } else {

                    return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
                }  

            }else {     

                return response()->json(['success' => $this->failureStatus, 'message' => $this->printCorrectMobileNumber() ], $this->failureStatusCode );
            }

        } else {

            return response()->json(['success' => $this->failureStatus, 'message' => $this->printMobileNoMissing() ], $this->failureStatusCode );
        }            

    } 


    public function userTransactionProcess(Request $request) {
        
        $input = $request->all();
        
        $userId = $input['user_id'];
        $vendorId = $input['vendor_id'];
        $loyaltyId = $input['loyalty_id'];
    
        if( !empty( $userId ) && !empty( $vendorId ) && !empty( $loyaltyId ) ) {
            
            $transactionDetail = new Transaction();
            $getTransactionDetails = $transactionDetail->getTransactionsDetails( $vendorId, $userId, $loyaltyId );
            
            if( ! $getTransactionDetails->isEmpty() ) {
                
                $response = array();
                $i = Config::get('constant.NUMBER.ZERO');        
                foreach($getTransactionDetails as $key => $getTransactionDetail){
                    $response[$i]['transaction_id'] =  $getTransactionDetail['transaction_id'];
                    $response[$i]['user_bill_amount'] =  $getTransactionDetail['user_bill_amount'];
                    //$response[$i]['user_bill_amount'] = html_entity_decode(CustomHelper::get_currency_symbol('INR'), ENT_QUOTES)."".$getTransactionDetail['user_bill_amount'];
                    $response[$i]['image'] =  ( isset( $getTransactionDetail['bill_path'] ) && !empty( $getTransactionDetail['bill_path'] ) ? $getTransactionDetail['bill_path'] : ' ');
                    $response[$i]['redeem_status'] = ( ( $getTransactionDetail['transaction_status'] == "Approved" ) ? "Success" : "N/A" );
                    $response[$i]['date_format'] = CustomHelper::getZoinDateandTimeFormat( $getTransactionDetail['creation_date'] );   
                    $i++;
                }

                return response()->json(['success' => $this->successStatus, 'message' => $response ], $this->successStatusCode );
                
            } else {
                return response()->json(['success' => $this->failureStatus, 'message' => $this->printNoRecords() ], $this->failureStatusCode );
            }

        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->VendorAndUserAndLoyalty() ], $this->failureStatusCode );
        }  
        
    }    

    public function userNotification(Request $request) {
      
        $input = $request->all();
        $userId = $input['user_id'];
       
        if( isset( $userId ) ) {
         
            $notificationDetail = new Notification();
            $getnotificationDetails = $notificationDetail->getMerchantNotificationsDetails( $userId );
            
            //echo "<pre>";
            //print_r($getnotificationDetails); exit;
            
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
    
        } else {
            
            return response()->json(['success' => $this->failureStatus, 'message' => $this->printUserIDMissing() ], $this->failureStatusCode );
        }  
        
    } 

}
