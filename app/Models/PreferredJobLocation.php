<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\PreferredJobLocation
 *
 * @property int $id
 * @property string $preferred_location_name
 * @property int|null $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
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
        'created_at', 'updated_at', 'deleted_at'
    ];

    public static function getAllPreferrefJobLocation()
    {
        return static::where('is_active', 1)->get()->toArray();
    }
}
