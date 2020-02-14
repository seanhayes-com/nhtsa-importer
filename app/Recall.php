<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Cache;

class Recall extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recall';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    

    protected $fillable = [
    
    'ModelYear', 'Make', 'Model', 'Manufacturer', 'NHTSACampaignNumber', 'ReportReceivedDate', 'Component', 'Summary'
    
    ];


}
