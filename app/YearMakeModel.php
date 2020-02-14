<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Cache;

class YearMakeModel extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'yearmakemodel';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    

    protected $fillable = [
    
    'ModelYear', 'Make'
    
    ];


}
