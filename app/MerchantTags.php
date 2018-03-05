<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class MerchantTags extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "merchant_tags";
        
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
        'tag_name'
    ];


    public static function saveMerchantTag( $tagName ){

        $tag = new MerchantTags();
        $tag->tag_name = $tagName;
        $tag->save();

        return ( isset($tag->id) && !empty($tag->id) ? $tag->id : '' );
    }

    public static function getMerchantTags( $tag ){
        
        $data = MerchantTags::where("tag_name", '=', $tag)->select('id','tag_name')->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

    public static function getMerchantTagLists( $vendorId ){
 
        $tag = DB::table('merchant_tags as mt')
                ->select("mt.id","mt.tag_name","tm.vendor_id")
                ->join('tag_merchants as tm', 'mt.id', '=', 'tm.tag_id')
                ->where('tm.vendor_id', '=', $vendorId )
                ->orderBy('tm.id', 'ASC')
                ->get();
        
        return ( isset($tag) && !empty($tag) ? $tag : '' );          
    
    }

   

}
