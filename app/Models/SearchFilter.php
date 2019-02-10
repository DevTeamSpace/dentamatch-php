<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SearchFilter
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $search_filter
 * @property string|null $city
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter newQuery()
 * @method static \Illuminate\Database\Query\Builder|SearchFilter onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereSearchFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchFilter whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|SearchFilter withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SearchFilter withoutTrashed()
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
