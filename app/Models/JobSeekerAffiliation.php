<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobSeekerAffiliation
 *
 * @property int $id
 * @property int $user_id
 * @property int $affiliation_id
 * @property string|null $other_affiliation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at   SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @property-read User $seeker
 * @property-read Affiliation $affiliation
 *
 * @method static Builder|JobSeekerAffiliation newModelQuery()
 * @method static Builder|JobSeekerAffiliation newQuery()
 * @method static Builder|JobSeekerAffiliation query()
 * @method static Builder|JobSeekerAffiliation whereAffiliationId($value)
 * @method static Builder|JobSeekerAffiliation whereCreatedAt($value)
 * @method static Builder|JobSeekerAffiliation whereDeletedAt($value)
 * @method static Builder|JobSeekerAffiliation whereId($value)
 * @method static Builder|JobSeekerAffiliation whereOtherAffiliation($value)
 * @method static Builder|JobSeekerAffiliation whereUpdatedAt($value)
 * @method static Builder|JobSeekerAffiliation whereUserId($value)
 * @mixin \Eloquent
 */
class JobSeekerAffiliation extends Model
{
    protected $table = 'jobseeker_affiliations';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    public function seeker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class);
    }

    public static function getUserAffiliationList($userId)
    {
        return static::select(['affiliation_id as affiliationId', 'other_affiliation as otherAffiliation'])
            ->where('user_id', $userId)->orderBy('affiliation_id')->get()->toArray();
    }

    public static function getJobSeekerAffiliation($userId)
    {
        return static::select(['affiliation_id as affiliationId', 'affiliations.affiliation_name as affiliationName', 'other_affiliation as otherAffiliation'])
            ->join('affiliations', 'affiliations.id', '=', 'jobseeker_affiliations.affiliation_id')
            ->where('user_id', $userId)
            ->where('affiliations.is_active', 1)
            ->orderBy('affiliation_id')->get()->toArray();
    }

}
