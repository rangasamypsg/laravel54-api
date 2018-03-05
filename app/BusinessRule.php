<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessRule extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "business_rule";
    
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
         'business_type','max_loyalty_amount','zoin_points'
     ];

     public function getBusinessBillAmount(){
        
        $billAmount = BusinessRule::orderBy('max_loyalty_amount', 'ASC')->select('max_loyalty_amount','zoin_points')->get();

        return  ( isset( $billAmount ) && !empty( $billAmount ) ? $billAmount : '');
     }

     public function getBillAmountBasedZoinPoint( $billAmt ){
        
        $billAmount = BusinessRule::where( 'max_loyalty_amount','=',$billAmt )->orderBy('max_loyalty_amount', 'ASC')->select('zoin_points')->first();

        return  ( isset( $billAmount ) && !empty( $billAmount ) ? $billAmount : '');
     }
}
