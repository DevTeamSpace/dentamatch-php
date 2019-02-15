<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\SearchFilter
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $search_filter
 * @property string|null $city
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static bool|null forceDelete()
 * @method static Builder|SearchFilter newModelQuery()
 * @method static Builder|SearchFilter newQuery()
 * @method static QueryBuilder|SearchFilter onlyTrashed()
 * @method static Builder|SearchFilter query()
 * @method static bool|null restore()
 * @method static Builder|SearchFilter whereCity($value)
 * @method static Builder|SearchFilter whereCreatedAt($value)
 * @method static Builder|SearchFilter whereDeletedAt($value)
 * @method static Builder|SearchFilter whereId($value)
 * @method static Builder|SearchFilter whereSearchFilter($value)
 * @method static Builder|SearchFilter whereUpdatedAt($value)
 * @method static Builder|SearchFilter whereUserId($value)
 * @method static QueryBuilder|SearchFilter withTrashed()
 * @method static QueryBuilder|SearchFilter withoutTrashed()
 * @mixin \Eloquent
 */
class SearchFilter extends Model
{
    use SoftDeletes;

    protected $table = 'search_filters';
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

    public static function createFilter($userId, $data)
    {
        $searchFilterModel = static::where('user_id', $userId)->first();
        if (!$searchFilterModel) {
            $searchFilterModel = new SearchFilter();
        }
        $searchFilterModel->search_filter = json_encode($data);
        $searchFilterModel->user_id = $userId;
        $searchFilterModel->city = $data['city'];
        $searchFilterModel->save();
    }

    public static function getFiltersOnLogin($userId)
    {
        $return = null;
        $searchFilterModel = static::where('user_id', $userId)->first();
        if ($searchFilterModel) {
            $return = json_decode($searchFilterModel->search_filter);
        }
        return $return;
    }
}
