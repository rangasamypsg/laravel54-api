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
	'name' => 'Rangasamy',
	'Email' => [
		'admin-email' => 'ranga@thegang.in',        
    ],
	
	'pagination' => [
		'perPage' => '3',        
    ],
  'Project' => [
    'title' => 'Rangasamy',        
  ],
  'STATUS' => [
        'Success' => 200,
        'Failure' => 400,
  ],
  'SMS' => [
    'AUTHENTICATION_KEY' => "154154AiJK22Ce592bdc61",
    'SENDER_ID' => "OTZOIN",
    'ROUTE' => 4,
    'COUNTRY_CODE' => 91,
  ],  

  'Date_Format' => "+5 hour +30 minutes"
];