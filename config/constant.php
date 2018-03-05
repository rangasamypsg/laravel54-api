<?php
use Illuminate\Support\Facades\Config;
return [
    /*
    |--------------------------------------------------------------------------
    | Pagination Limit
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views. Of course
    | the usual Laravel view path has already been registered for you.
    |
    */
    'MERCHANT' => 'V',
    'USER' => 'U',
    'LOYALTY' => 'Loyalty',
    'VENDOR' => 'vendor',
    'USER' => 'U',
    'MERCHANT_CODE' => 'ZMER',
    'LOYALTY_CODE' => 'ZLTY',
    'USER_CODE' => 'ZUSR',
    'TRANSACTION_TYPE' => 'ZOIN',
    'BASE_ENCODE' => "data:image/gif;base64,",
    'TIMESTAMP' => time(),
    'SYMBOL' => [
        'PLUS' => "+",
        'MINUS' => "-",
    ],
    'EXTENSION' => [
        'PNG' => 'png',
        'GIF' => 'gif',
        'BMP' => 'bmp',         
    ],
    'SIZE' => [
        'QUALITY' => 90,
    ],
    'Email' => [
        'admin-email' => 'ranga@thegang.in',
        'Name' => 'Rangasamy',        
    ],
	'LOYALTY_STATUS' => [
		'CREATED' => 'Created',        
		'OPEN' => 'Open',        
		'CLOSED' => 'Closed',        
        'DENIED' => 'Denied',
        'APPROVED' => 'Approved',        
        'UNAPPROVED' => 'Unapproved',        
        'INACTIVE' => 'Inactive',        
        'ACTIVE' => 'Active',        
        'ACTIVATE' => 'Activate',        
    ],
    'APPROVED' => 'Approved',
    'NOT_APPROVED' => 'Not Approved',
    'DELETED' => 'Deleted',
    'ENCRYPT' => [
        'KEY' => '123456789'
    ],
    'NUMBER' => [
        'ZERO' => 0,
        'ONE' => 1,
        'TWO' => 2,
        'THREE' => 3,
        'FOUR' => 4,
        'FIVE' => 5,
        'SIX' => 6,
        'SEVEN' => 7,
        'EIGHT' => 8,
        'NINE' => 9,
        'TEN' => 10,
        'NINETYNINE' => 99,
    ],
    'TEXT' => [
        'ZERO' => 'Zero',
        'ONE' => 'One',
        'TWO' => 'Two',
        'THREE' => 'Three',
        'FOUR' => 'Four', 
        'FIVE' => 'Five', 
        'SIX' => 'Six', 
        'YES' => 'yes',
        'NO' => 'no',        
    ],
    'MERCHANT_STATUS' => [
		'APPROVED' => 'Approved',
		'UNAPPROVED' => 'Unapproved',        
        'PENDING' => 'Pending',
		'BLOCKED' => 'Block',
    ],
    'EMPTY' => "N/A",
    'NORECORDS' => "No Records",
    'HOLIDAY' => "Holi day",
    'FEAUTERS' => [
        'ITEMS-VEG' => 'Veg',
        'ITEMS-NVEG' => 'Non Veg',
        'ITEMS-NON-VEG' => 'Veg/Non Veg',
        'ROOMS-AC' => 'Ac',
        'ROOMS-NAC' => 'Non Ac',
        'ROOMS-AC-NON' => 'Ac/Non Ac',
        'CARDPAYMENT' => 'Card Payment',        
        'WIFI' => 'Wifi',
        'RESTROOM' => 'Rest Room',
		'SELFSERVICE' => 'Self Services',
		'PARKING' => 'Parking',
		'DISABLEDACCESS' => 'Disabled Access',
		'CCTV' => 'cctv',
		'ALCOHOLSERVING' => 'Alcohol Serving',		 
    ],
    'NOTIFICATION' => [
        'MERCHANT' => [
            'LOGIN' => "MERID-%s logged in.",               	 
            'LOGOUT' => "MERID-%s logged out.",
            'LOYALTY_SUBMIT' => "MERID-%s submitted | LTYID-%s successfully",
            //'LOYALTY_SUBMIT' => "Your Loyalty has been submitted, Please note that rewards will take about a successful review of your mission",               	 
            'LOYALTY_ACTIVE' => "MERID-%s activated | LTYID-%s successfully",               	 
            'EDIT_PROFILE' => "MERID-%s edited company description successfully",               	 
            'EDIT_TAG' => "MERID-%s edited Tag successfully",               	 
            'TRANSACTION' => "USRID-%s redeemed | LTYID-%s successfully",  
            'USRBALANCE' => "MERID-%s added | %s Zoin by USRID-%s successfully",  
            //'MERBALANCE' => "Merchant zoin point has been deducted",  
            'MERBALANCE' => "MERID-%s deducted | %s Zoin by USRID-%s successfully",
        ],
        'USER' => [
            'LOGIN' => "Login successful",               	 
            'LOGOUT' => "Logout successful",
            'REDEEMED_CODE' => "Redeem code created successfully",
        ],
        'POPUP' => [
            'ADD_LOYALTY' => "Please Submit Your 1st loyalty",               	 
            'INACTIVE' => "Your loyalty is created successfully. Please wait for our support to activate",
            'ACTIVATE' => "Loyalty approved! Please activate",
            'OPEN' => "Loyalty open",
            'CLOSED' => "Loyalty closed",
            'DENIED' => "Loyalty denied",
        ],
        'SMS' => [
            'REGISTER' => "Registration created successfully",
            'LOYALTY_SUBMIT' => "Loyalty created successfully",
        ],                 	 
    ],
    'NOTIFICATION-IMG' => [
        'LOGIN' => 'login.png',               	 
        'LOGOUT' => 'logout.png',
        'LOYALTY_SUBMIT' => "loyalty_submit.png",               	 
        'LOYALTY_ACTIVE' => "loyalty_active.png",               	 
        'EDIT_PROFILE' => "edit_profile.png",               	 
        'REDEEMED_CODE' => "redeem_code.png",               	 
        'TRANSACTION' => "transaction.png",               	 
        'DEDUCTION' => "deduction.png",               	 
        'ADD' => "add.png",               	 
    ],
    'MERCHANTS' => [
        'BALANCE' => 500
    ],
    'ZOINUSER' => [
        'MERCHANT' => 'Merchant',
        'USER' => 'User',
        'TRANSACTION' => 'Transaction',
        'LOYALTY' => 'Loyalty',
        'ADMIN' => 'Admin',
        'NOTIFICATION' => 'Notification',
    ],
    'FORMAT-CODE' => [
        'MERCHANT_CODE' => 'ZVR',
        'USER_CODE' => 'ZUR',
        'LOYALTY_CODE' => 'ZLY',
        'TRANSACTION_CODE' => 'ZTN',
        'NOTIFICATION_CODE' => 'ZTXN',
    ],
    'AUTOINCREMENT' => [
        'DEFAULT' => '001',
        'D-ZERO' => '00',
    ]    

];        