<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\State
 *
 * @property int $id
 * @property string $state_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|State newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|State newQuery()
 * @method static \Illuminate\Database\Query\Builder|State onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|State query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|State whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereStateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|State whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|State withTrashed()
 * @method static \Illuminate\Database\Query\Builder|State withoutTrashed()
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
