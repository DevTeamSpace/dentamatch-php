<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Affiliation
 *
 * @property int $id
 * @property string $affiliation_name
 * @property int $is_active
 * @property Carbon $created_at1
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builder|Affiliation newModelQuery()
 * @method static Builder|Affiliation newQuery()
 * @method static \Illuminate\Database\Query\Builder|Affiliation onlyTrashed()
 * @method static Builder|Affiliation query()
 * @method static bool|null restore()
 * @method static Builder|Affiliation whereAffiliationName($value)
 * @method static Builder|Affiliation whereCreatedAt($value)
 * @method static Builder|Affiliation whereDeletedAt($value)
 * @method static Builder|Affiliation whereId($value)
 * @method static Builder|Affiliation whereIsActive($value)
 * @method static Builder|Affiliation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Affiliation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Affiliation withoutTrashed()
 * @mixin \Eloquent
 */
class Affiliation extends Model
{
    use SoftDeletes;

    protected $table = 'affiliations';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    public static function getAffiliationList()
    {
        $affiliationModel = static::select('affiliations.id as affiliationId', 'affiliation_name as affiliationName',
                                        'affiliations.is_active as isActive', 'affiliations.created_at as createdAt')
                                ->where('affiliations.is_active', 1)->orderBy('affiliations.id')->get();

        if($affiliationModel) {
            $list = $affiliationModel->toArray();
        }

        return $list;
    }

}
