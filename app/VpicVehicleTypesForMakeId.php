<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Cache;

class VpicVehicleTypesForMakeId extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vpic_types_make_id';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    

    protected $fillable = [
    
    'Make_ID', 'VehicleTypeId', 'VehicleTypeName'
    
    ];


}
