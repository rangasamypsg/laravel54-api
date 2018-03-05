<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('welcome');
});

//Route::group(['prefix' => 'api/v1','middleware' => ['web']], function () {
Route::group(['middleware' => ['web','XSS']], function () {

    //Api Routes
    //Merchant Registration Process
    Route::post('registration', ['as' => 'Merchant.registration' , 'uses' => 'MerchantApiController@registration']);
    Route::post('merchent_login', ['as' => 'Merchant.doLogin' , 'uses' => 'MerchantApiController@doLogin']);
    Route::post('merchent_mobile_login', ['as' => 'Merchant.doMobileLogin' , 'uses' => 'MerchantApiController@doMobileLogin']);

    //Merchant Registration Email Process
    Route::get('register/verify/{confirmationCode}', ['as' => 'Merchant.ConfirmationCode','uses' => 'MerchantApiController@confirm']);

    //Reset Password Merchant
    Route::post('resetpassword', ['as' => 'Merchant.ResetPassword' , 'uses' => 'MerchantApiController@resetPassword']);
    Route::post('resendotp', ['as' => 'Merchant.ResendOtp' , 'uses' => 'MerchantApiController@reSendOtpGeneration']);
    Route::post('verify_otp', ['as' => 'Merchant.VerifyOtp' , 'uses' => 'MerchantApiController@verifyOtp']);
    Route::post('login_verify_otp', ['as' => 'Merchant.LoginVerifyOtp' , 'uses' => 'MerchantApiController@loginVerifyOtp']);
    Route::post('logout_update', ['as' => 'Merchant.logoutPassword' , 'uses' => 'MerchantApiController@logoutPassword']);
    
    //Route::post('verify_forgot_otp', ['as' => 'Merchant.VerifyForgotOtp' , 'uses' => 'MerchantApiController@verifyForgotOtp']);
    Route::post('updatepassword', ['as' => 'Merchant.UpdatePassword' , 'uses' => 'MerchantApiController@updatepassword']);

    //Merchant offer type and zoin point 
    Route::post('loyalty_offer_type', ['as' => 'Merchant.OfferType' , 'uses' => 'MerchantApiController@loyaltyOfferType']);
    Route::post('loyalty_zoin_point', ['as' => 'Merchant.LoyaltyZoinPoint' , 'uses' => 'MerchantApiController@LoyaltyZoinPoint']);

    //Loyalty Api Services for Merchant
    Route::post('loyalty', ['as' => 'Merchant.AddLoyalty' , 'uses' => 'MerchantApiController@addLoyalty']);
    Route::post('loyalty_count_check', ['as' => 'Merchant.LoyaltyCount' , 'uses' => 'MerchantApiController@loyaltyCount']);
    Route::post('loyalty-list', ['as' => 'Merchant.LoyaltyList' , 'uses' => 'MerchantApiController@LoyaltyList']);
    Route::post('view_loyalty_list', ['as' => 'Merchant.ViewLoyaltyList' , 'uses' => 'MerchantApiController@ViewLoyaltyList']);
    Route::post('merchant_loyalty_status', ['as' => 'Merchant.LoyaltyStatus' , 'uses' => 'MerchantApiController@merchantLoyaltyStatus']);
    Route::post('merchant_social_media', ['as' => 'Merchant.SocialMedia' , 'uses' => 'MerchantApiController@merchantSocialMedia']);

    //Merchant Profile List
    Route::post('sidebar-loyalty-list', ['as' => 'Merchant.SideBarLoyaltyList' , 'uses' => 'MerchantApiController@SideBarLoyaltyList']);
    Route::post('merchant-profile-list', ['as' => 'Merchant.MerchantProfileList' , 'uses' => 'MerchantApiController@MerchantProfileList']);
    Route::post('merchant-edit-profile', ['as' => 'Merchant.MerchantEditProfile' , 'uses' => 'MerchantApiController@MerchantEditProfile']);
    Route::post('merchant-edit-profile-list', ['as' => 'Merchant.MerchantEditProfileList' , 'uses' => 'MerchantApiController@merchantEditProfileList']);
    Route::post('merchant_company_description', ['as' => 'Merchant.merchantCompanyDescription' , 'uses' => 'MerchantApiController@merchantCompanyDescription']);
    Route::post('merchant_tags', ['as' => 'Merchant.merchantTags' , 'uses' => 'MerchantApiController@merchantTags']);
    Route::post('merchant_tag_delete', ['as' => 'Merchant.merchantTagDelete' , 'uses' => 'MerchantApiController@merchantTagDelete']);
    Route::post('merchant_tag_list', ['as' => 'Merchant.merchantTagList' , 'uses' => 'MerchantApiController@merchantTagList']);
    Route::post('merchant_block', ['as' => 'Merchant.merchantBlock' , 'uses' => 'MerchantApiController@merchantBlock']);
    Route::post('popup_menu', ['as' => 'Merchant.LoyaltyPopupMenu' , 'uses' => 'MerchantApiController@LoyaltyPopupMenu']);

    Route::post('merchant_redeem_verify', ['as' => 'Merchant.MerchantRedeemVerify' , 'uses' => 'MerchantApiController@merchantRedeemVerify']);
    Route::post('merchant_transaction', ['as' => 'Merchant.MerchantTransaction' , 'uses' => 'MerchantApiController@MerchantTransaction']);
    Route::post('merchant_notification', ['as' => 'Merchant.MerchantNotification' , 'uses' => 'MerchantApiController@merchantNotification']);
    Route::post('user_notification', ['as' => 'User.UserNotification' , 'uses' => 'UserApiController@userNotification']);

    //User Registration Process
    Route::post('user_registration', ['as' => 'User.Registration' , 'uses' => 'UserApiController@userRegistration']);
    Route::post('user_mobile_login', ['as' => 'User.doMobileLogin' , 'uses' => 'UserApiController@doMobileLogin']);

    //User Profile List
    Route::post('user_sidebar_list', ['as' => 'User.UserSideBarList' , 'uses' => 'UserApiController@userSideBarList']);
    Route::post('user_profile_list', ['as' => 'User.UserProfileList' , 'uses' => 'UserApiController@userProfileList']);
    Route::post('user_edit_profile', ['as' => 'User.UserEditProfile' , 'uses' => 'UserApiController@userEditProfile']);
    Route::post('user_edit_profile_list', ['as' => 'User.UserEditProfileList' , 'uses' => 'UserApiController@userEditProfileList']);    

    //Loyalty Api Services for User
    Route::post('user_loyalty_detail_list', ['as' => 'User.UserLoyaltyDetailList' , 'uses' => 'UserApiController@userLoyaltyDetailList']);
    Route::get('user_explore_list', ['as' => 'User.userExploreList' , 'uses' => 'UserApiController@userExploreList']);
    Route::post('user_profile_explore_list', ['as' => 'User.userProfileExploreList' , 'uses' => 'UserApiController@userProfileExploreList']);
    Route::post('user_explore_image_list', ['as' => 'User.userExploreImageList' , 'uses' => 'UserApiController@userExploreImageList']);
    Route::post('user_explore_confirm', ['as' => 'User.userExploreConfirm' , 'uses' => 'UserApiController@userExploreConfirm']);
    Route::post('user_redeem_list', ['as' => 'User.userRedeemList' , 'uses' => 'UserApiController@userRedeemList']);
     
    //User OTP Send and Verify
    Route::post('user_resend_otp', ['as' => 'User.ResendOtp' , 'uses' => 'UserApiController@reSendOtpGeneration']);
    Route::post('user_verify_otp', ['as' => 'User.VerifyOtp' , 'uses' => 'UserApiController@userVerifyOtp']);
    Route::post('user_login_verify_otp', ['as' => 'User.UserLoginVerifyOtp' , 'uses' => 'UserApiController@userLoginVerifyOtp']);

    //Transaction loyalty process
    Route::post('merchant_transaction_loyalty', ['as' => 'Merchant.MerchantTransactionLoyalty' , 'uses' => 'MerchantApiController@MerchantTransactionLoyalty']);
    Route::post('user_redeemed_list', ['as' => 'User.userRedeemedList' , 'uses' => 'UserApiController@userRedeemedList']);

    Route::post('user_transaction_list', ['as' => 'User.UserTransactionProcess' , 'uses' => 'UserApiController@userTransactionProcess']);
    Route::post('merchant_transaction_list', ['as' => 'Merchant.MerchantTransactionProcess' , 'uses' => 'MerchantApiController@merchantTransactionProcess']);
    //User Logout
    Route::post('user_logout_update', ['as' => 'User.UserLogoutPassword' , 'uses' => 'UserApiController@userLogoutPassword']);
    Route::post('app_version', ['as' => 'Merchant.appVersion' , 'uses' => 'MerchantApiController@appVersion']);
});



