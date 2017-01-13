<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends \Eloquent
{
    use Eloquence, Mappable;
    use SoftDeletes;
    
    protected $table        = 'locations';
    protected $primaryKey   = 'id';
   
    protected $maps          = [
        'locationId' => 'id',
        'freeTrialPeriod' => 'free_trial_period',
        'isActive'    => 'is_active',
        ];
    protected $hidden       = ['id','is_active','created_at','updated_at','deleted_at'];
    protected $fillable     = ['locationId','freeTrialPeriod','isActive'];
    protected $appends      = ['locationId','freeTrialPeriod','isActive'];

    protected $dates = ['deleted_at'];
    
    public static function getList(){
        return static::where('is_active',1)->pluck('zipcode')->all();
    }    
}
