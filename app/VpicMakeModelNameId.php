<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Cache;

class VpicMakeModelNameId extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vpic_make_model_name_id';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    

    protected $fillable = [
    
    'Make_ID', 'Make_Name', 'Model_ID', 'Model_Name'
    
    ];


}
