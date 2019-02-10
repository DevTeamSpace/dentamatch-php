<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\JobSeekerAffiliation
 *
 * @property int $id
 * @property int $user_id
 * @property int $affiliation_id
 * @property string|null $other_affiliation
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation newQuery()
 * @method static \Illuminate\Database\Query\Builder|JobSeekerAffiliation onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereAffiliationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereOtherAffiliation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobSeekerAffiliation whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|JobSeekerAffiliation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|JobSeekerAffiliation withoutTrashed()
 * @mixin \Eloquent
 */
class JobSeekerAffiliation extends Model
{
    use SoftDeletes;

    protected $table = 'jobseeker_affiliations';
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

    public static function getUserAffiliationList($userId)
    {
        return static::select('affiliation_id as affiliationId', 'other_affiliation as otherAffiliation')
            ->where('user_id', $userId)->orderBy('affiliation_id')->get()->toArray();
    }

    public static function getJobSeekerAffiliation($userId)
    {
        $query = static::select('affiliation_id as affiliationId', 'affiliations.affiliation_name as affiliationName', 'other_affiliation as otherAffiliation')
            ->join('affiliations', 'affiliations.id', '=', 'jobseeker_affiliations.affiliation_id')
            ->where('user_id', $userId)
            ->where('affiliations.is_active', 1)
            ->orderBy('affiliation_id');

        $list = $query->get()->toArray();

        return $list;
    }

}
