<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Schooling
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $school_name
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling newQuery()
 * @method static \Illuminate\Database\Query\Builder|Schooling onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereSchoolName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schooling whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Schooling withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Schooling withoutTrashed()
 * @mixin \Eloquent
 */
class Schooling extends Model
{
    use SoftDeletes;
  
    protected $table  = 'schoolings';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at'
    ];
    
    public static function getScoolingList()
    {
        $query = static::select('schoolings.id as parentId', 'schoolingsChild.id as childId', 'schoolings.school_name as schoolName', 'schoolingsChild.school_name as schoolChildName')
                ->leftjoin('schoolings AS schoolingsChild','schoolingsChild.parent_id','=','schoolings.id')
                ->where('schoolingsChild.is_active', 1)
                ->whereNull('schoolings.parent_id')
                ->orderBy('schoolings.id')
                ->orderBy('schoolingsChild.id');
        
        $list = $query->get()->toArray();
        return $list;
    }
}
