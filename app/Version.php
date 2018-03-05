<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
     /**
    * The table associated with the model.
    *
    * @var string
    */
    public $table = "version";
    
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version_name'
    ];
}
