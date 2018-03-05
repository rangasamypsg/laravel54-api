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

	//use App\Helpers\CustomHelper;
	//echo $message = CustomHelper::fooBar(); exit;

    return view('welcome');
});


//Api Routes
//Merchant Registration Process
Route::post('registration', ['as' => 'Merchant.registration' , 'uses' => 'MerchantApiController@registration']);
Route::post('merchent_login', ['as' => 'Merchant.doLogin' , 'uses' => 'MerchantApiController@doLogin']);

//Merchant Registration Email Process
Route::get('register/verify/{confirmationCode}', ['as' => 'Merchant.ConfirmationCode','uses' => 'MerchantApiController@confirm']);

//Reset Password Merchant
Route::post('resetpassword', ['as' => 'Merchant.ResetPassword' , 'uses' => 'MerchantApiController@resetPassword']);
Route::post('verify_otp', ['as' => 'Merchant.VerifyOtp' , 'uses' => 'MerchantApiController@verifyOtp']);
Route::post('updatepassword', ['as' => 'Merchant.UpdatePassword' , 'uses' => 'MerchantApiController@updatepassword']);

//Merchant offer type and zoin point 
//Route::get('loyalty_offer_type', ['as' => 'Merchant.OfferType' , 'uses' => 'MerchantApiController@loyaltyOfferType']);
Route::post('loyalty_zoin_point', ['as' => 'Merchant.LoyaltyZoinPoint' , 'uses' => 'MerchantApiController@LoyaltyZoinPoint']);

//Loyalty Api Services for Merchant
Route::post('loyalty', ['as' => 'Merchant.AddLoyalty' , 'uses' => 'MerchantApiController@addLoyalty']);
Route::post('loyalty-list', ['as' => 'Merchant.LoyaltyList' , 'uses' => 'MerchantApiController@LoyaltyList']);

//Merchant Profile List
Route::post('sidebar-loyalty-list', ['as' => 'Merchant.SideBarLoyaltyList' , 'uses' => 'MerchantApiController@SideBarLoyaltyList']);
Route::post('merchant-profile-list', ['as' => 'Merchant.MerchantProfileList' , 'uses' => 'MerchantApiController@MerchantProfileList']);

/* Route::group( array('before' => 'auth'), function() {
    
    Route::get('loyalty_offer_type', ['as' => 'Merchant.OfferType' , 'uses' => 'MerchantApiController@loyaltyOfferType']); 

}); */

Route::group(['prefix' => 'admin','middleware' => ['web']], function () {
    Route::get('loyalty_offer_type', ['as' => 'Merchant.OfferType' , 'uses' => 'MerchantApiController@loyaltyOfferType']);
});


Route::group(['prefix'=>'api/v1','middleware'=>'auth:api'], function(){
    
   //Route::get('posts', ['as' => 'Merchant.index' , 'uses' => 'MerchantApiController@index']);
   
});

Route::group(['prefix'=>'api/v1','middleware' => ['web']], function() {

    //Route::get('loyalty_offer_type', ['as' => 'Merchant.OfferType' , 'uses' => 'MerchantApiController@loyaltyOfferType']);

});


