<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static Builder|Location newModelQuery()
 * @method static Builder|Location newQuery()
 * @method static QueryBuilder|Location onlyTrashed()
 * @method static Builder|Location query()
 * @method static bool|null restore()
 * @method static Builder|Location whereCreatedAt($value)
 * @method static Builder|Location whereDeletedAt($value)
 * @method static Builder|Location whereDescription($value)
 * @method static Builder|Location whereFreeTrialPeriod($value)
 * @method static Builder|Location whereId($value)
 * @method static Builder|Location whereIsActive($value)
 * @method static Builder|Location whereUpdatedAt($value)
 * @method static Builder|Location whereZipcode($value)
 * @method static QueryBuilder|Location withTrashed()
 * @method static QueryBuilder|Location withoutTrashed()
 * @mixin \Eloquent
 */
class Location extends \Eloquent
{
    use Eloquence, Mappable;
    use SoftDeletes;

    protected $table = 'locations';
    protected $primaryKey = 'id';

    protected $maps = [
        'locationId'      => 'id',
        'freeTrialPeriod' => 'free_trial_period',
        'isActive'        => 'is_active',
    ];
    protected $hidden = ['id', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['locationId', 'freeTrialPeriod', 'isActive'];
    protected $appends = ['locationId', 'freeTrialPeriod', 'isActive'];

    protected $dates = ['deleted_at'];

    public static function getList($zipcode)
    {
        return static::where('zipcode', $zipcode)->where('is_active', 1)->pluck('zipcode')->all();
    }
}
