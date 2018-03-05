<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "business_types";

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
         'business_type'
     ];

     public function getBusinessType(){
        
        $businessType = BusinessType::orderBy('business_type', 'ASC')->select('business_type')->orderBy('id', 'DESC')->get();

        return  ( isset( $businessType ) && !empty( $businessType ) ? $businessType : '');
     }
}
