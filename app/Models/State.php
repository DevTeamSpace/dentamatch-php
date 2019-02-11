<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\State
 *
 * @property int $id
 * @property string $state_name
 * @property int $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 *
 * @method static bool|null forceDelete()
 * @method static Builder|State newModelQuery()
 * @method static Builder|State newQuery()
 * @method static QueryBuilder|State onlyTrashed()
 * @method static Builder|State query()
 * @method static bool|null restore()
 * @method static Builder|State whereCreatedAt($value)
 * @method static Builder|State whereDeletedAt($value)
 * @method static Builder|State whereId($value)
 * @method static Builder|State whereIsActive($value)
 * @method static Builder|State whereStateName($value)
 * @method static Builder|State whereUpdatedAt($value)
 * @method static QueryBuilder|State withTrashed()
 * @method static QueryBuilder|State withoutTrashed()
 * @mixin \Eloquent
 */
class State extends \Eloquent
{
    use Eloquence, Mappable;
    use SoftDeletes;

    protected $table = 'states';
    protected $primaryKey = 'id';

    protected $maps = [
        'stateId'   => 'id',
        'stateName' => 'state_name',
        'isActive'  => 'is_active',
    ];
    protected $hidden = ['id', 'is_active', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['stateId', 'stateName', 'isActive'];
    protected $appends = ['stateId', 'stateName', 'isActive'];

    protected $dates = ['deleted_at'];

}
