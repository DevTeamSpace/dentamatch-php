<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Schooling
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $school_name
 * @property int $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at                    SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @property Schooling $parent
 * @property Schooling[]|Collection $children
 *
 * @method static Builder|Schooling newModelQuery()
 * @method static Builder|Schooling newQuery()
 * @method static Builder|Schooling query()
 * @method static Builder|Schooling whereCreatedAt($value)
 * @method static Builder|Schooling whereDeletedAt($value)
 * @method static Builder|Schooling whereId($value)
 * @method static Builder|Schooling whereIsActive($value)
 * @method static Builder|Schooling whereParentId($value)
 * @method static Builder|Schooling whereSchoolName($value)
 * @method static Builder|Schooling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Schooling extends Model
{
    protected $table = 'schoolings';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    public function parent()
    {
        return $this->belongsTo(Schooling::class);
    }

    public function children()
    {
        return $this->hasMany(Schooling::class, 'parent_id');
    }

    public static function getSchoolingList()
    {
        return static::select(['schoolings.id as parentId', 'schoolingsChild.id as childId', 'schoolings.school_name as schoolName', 'schoolingsChild.school_name as schoolChildName'])
            ->leftjoin('schoolings AS schoolingsChild', 'schoolingsChild.parent_id', '=', 'schoolings.id')
            ->where('schoolingsChild.is_active', 1)
            ->whereNull('schoolings.parent_id')
            ->orderBy('schoolings.id')
            ->orderBy('schoolingsChild.id')
            ->get()
            ->toArray();
    }
}
