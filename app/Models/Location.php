<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Location
 *
 * @property int $id
 * @property int $zipcode
 * @property string $description
 * @property int $free_trial_period
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereFreeTrialPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Location whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Location withoutTrashed()
 * @mixin \Eloquent
 */
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
    
    public static function getList($zipcode){
        return static::where('zipcode',$zipcode)->where('is_active',1)->pluck('zipcode')->all();
    }    
}
