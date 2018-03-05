<?php

namespace App\Traits;

trait UserMessage {
   
    /**
	 * inits bank account with a particular account no and
	 * initial amount
	 * @param string $accountNo
	 * @param float  $initialAmount
	 */
 
	/* public function __construct() {
        
        $args = func_get_args();
		$num = func_num_args();
		if(method_exists($this,$f = 'init_' . $num)) {
			call_user_func_array(array($this,$f),$args);
        }
        
    } */
    
    /**
     * Set the view for the mail message.
     *
     * @param  array|string  $view
     * @param  array  $data
     * @return $this
     */  
    public static function printUserRegisterSuccess() {
       
        $data =  "Registration successful";

        return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }

    public static function printUserRegisterFalse() {
        
         $data =  "Registration not successful. Please register again";
 
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }
   
    public static function printRedeemCreated( ) {
        
         //$data =  "Thanks for $companyName loyalty please submit your mobile number to merchant for your checkins : $redeemCode";
         $data =  "Thanks for Confirming your order! you will get your Redeem Code Shortly";
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }

    public static function printRedeemNotCreated( ) {
        
         $data =  "Loyalty not created successfully";
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }

    public static function printAddMoreRedeem( ) {
        
         $data =  "you have already redeemed for this restaurant. click here to check your redeem list.";
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }
    
    public static function printUserIDMissing() {
        
         $data =  "User id is Missing";
 
         return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }

    public static function printLoyaltyCombination() {
        
        $data =  "Loyalty combination details is Missing";

        return  ( isset( $data ) && !empty( $data ) ? $data : '');
   }

   public static function printVendorAndUserAndLoyaltyMissing() {
        
        $data =  "Vendor and User and Loyalty details is Missing";

        return  ( isset( $data ) && !empty( $data ) ? $data : '');
    }

}
