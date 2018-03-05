<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class TagMerchants extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "tag_merchants";
        
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
        'vendor_id','tag_id'
    ];
    
    public static function saveTagMerchants( $vendorId, $tagId ){

        $tagMerchant = new TagMerchants();              
        $tagMerchant->vendor_id = $vendorId;            
        $tagMerchant->tag_id = $tagId;            
        $tagMerchant->save();

        return ( isset($tagMerchant->id) && !empty($tagMerchant->id) ? $tagMerchant->id : '' );
    }

    public static function removeTags( $vendorId ){

        DB::table('tag_merchants')->where('vendor_id', $vendorId)->delete();
         
    }
    
    public static function removeSelectTags( $vendorId, $tagId ){

        DB::table('tag_merchants')->where('vendor_id', $vendorId)->where('tag_id', $tagId)->delete();
         
    }

    public static function getTagMerchant( $vendorId, $tagId ){
        
        $data = TagMerchants::where('vendor_id', $vendorId)->where('tag_id', $tagId)->first();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

    public static function getTagCount( $vendorId ){
        
        $data = TagMerchants::where('vendor_id', $vendorId)->count();
        
        return ( isset($data) && !empty($data) ? $data : '' );          
    }

}
