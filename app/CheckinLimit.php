<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckinLimit extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "checkin_limit";
    
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
         'maximum_checkin_available'
     ];

     public function getMaximumCheckinAvailable(){
        
        $maxCheckIn = CheckinLimit::orderBy('maximum_checkin_available', 'ASC')->select('maximum_checkin_available')->first();

        return  ( isset( $maxCheckIn ) && !empty( $maxCheckIn ) ? $maxCheckIn : '');
     }

}
