<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
 * @property Carbon|null $deleted_at              SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @method static Builder|Affiliation newModelQuery()
 * @method static Builder|Affiliation newQuery()
 * @method static Builder|Affiliation query()
 * @method static Builder|Affiliation whereAffiliationName($value)
 * @method static Builder|Affiliation whereCreatedAt($value)
 * @method static Builder|Affiliation whereDeletedAt($value)
 * @method static Builder|Affiliation whereId($value)
 * @method static Builder|Affiliation whereIsActive($value)
 * @method static Builder|Affiliation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Affiliation extends Model
{
    protected $table = 'affiliations';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    public static function getAffiliationList()
    {
        return static::select(['affiliations.id as affiliationId', 'affiliation_name as affiliationName',
                               'affiliations.is_active as isActive', 'affiliations.created_at as createdAt'])
            ->where('affiliations.is_active', 1)->orderBy('affiliations.id')->get()->toArray();
    }

}
