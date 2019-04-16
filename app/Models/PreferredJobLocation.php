<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\PreferredJobLocation
 *
 * @property int $id
 * @property string $preferred_location_name
 * @property int|null $is_active
 * @property int $anchor_zipcode
 * @property int $radius
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property Location[] $locations
 * @method static Builder|PreferredJobLocation newModelQuery()
 * @method static Builder|PreferredJobLocation newQuery()
 * @method static Builder|PreferredJobLocation query()
 * @method static Builder|PreferredJobLocation whereCreatedAt($value)
 * @method static Builder|PreferredJobLocation whereDeletedAt($value)
 * @method static Builder|PreferredJobLocation whereId($value)
 * @method static Builder|PreferredJobLocation whereIsActive($value)
 * @method static Builder|PreferredJobLocation wherePreferredLocationName($value)
 * @method static Builder|PreferredJobLocation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PreferredJobLocation extends Model
{
    protected $table = 'preferred_job_locations';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['preferred_location_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at' // todo soft delete?
    ];

    public function locations()
    {
        return $this->hasMany(Location::class, 'area_id');
    }

    public static function getAllPreferredJobLocation()
    {
        return static::where('is_active', 1)->get()->toArray();
    }
}
