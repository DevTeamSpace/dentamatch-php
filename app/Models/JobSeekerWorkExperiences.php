<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobSeekerWorkExperiences
 *
 * @property int $id
 * @property int $user_id
 * @property int $job_title_id
 * @property int $months_of_expereince
 * @property string $office_name
 * @property string $office_address
 * @property string $city
 * @property string|null $state
 * @property string|null $reference1_name
 * @property string|null $reference1_mobile
 * @property string|null $reference1_email
 * @property string|null $reference2_name
 * @property string|null $reference2_mobile
 * @property string|null $reference2_email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $seeker
 * @property-read JobTitles $jobTitle
 *
 * @method static bool|null forceDelete()
 * @method static Builder|JobSeekerWorkExperiences newModelQuery()
 * @method static Builder|JobSeekerWorkExperiences newQuery()
 * @method static QueryBuilder|JobSeekerWorkExperiences onlyTrashed()
 * @method static Builder|JobSeekerWorkExperiences query()
 * @method static bool|null restore()
 * @method static Builder|JobSeekerWorkExperiences whereCity($value)
 * @method static Builder|JobSeekerWorkExperiences whereCreatedAt($value)
 * @method static Builder|JobSeekerWorkExperiences whereDeletedAt($value)
 * @method static Builder|JobSeekerWorkExperiences whereId($value)
 * @method static Builder|JobSeekerWorkExperiences whereJobTitleId($value)
 * @method static Builder|JobSeekerWorkExperiences whereMonthsOfExpereince($value)
 * @method static Builder|JobSeekerWorkExperiences whereOfficeAddress($value)
 * @method static Builder|JobSeekerWorkExperiences whereOfficeName($value)
 * @method static Builder|JobSeekerWorkExperiences whereReference1Email($value)
 * @method static Builder|JobSeekerWorkExperiences whereReference1Mobile($value)
 * @method static Builder|JobSeekerWorkExperiences whereReference1Name($value)
 * @method static Builder|JobSeekerWorkExperiences whereReference2Email($value)
 * @method static Builder|JobSeekerWorkExperiences whereReference2Mobile($value)
 * @method static Builder|JobSeekerWorkExperiences whereReference2Name($value)
 * @method static Builder|JobSeekerWorkExperiences whereState($value)
 * @method static Builder|JobSeekerWorkExperiences whereUpdatedAt($value)
 * @method static Builder|JobSeekerWorkExperiences whereUserId($value)
 * @method static QueryBuilder|JobSeekerWorkExperiences withTrashed()
 * @method static QueryBuilder|JobSeekerWorkExperiences withoutTrashed()
 * @mixin \Eloquent
 */
class JobSeekerWorkExperiences extends Model
{
    use SoftDeletes;

    protected $table = 'jobseeker_work_experiences';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'deleted_at', 'created_at'
    ];

    public function seeker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitles::class, 'job_title_id');
    }

    public static function getParentWorkExperiences($userId) // todo why it is called parent?
    {
        $work = [];
        if ($userId) {
            $work = JobSeekerWorkExperiences::where('jobseeker_work_experiences.user_id', $userId)
                ->leftJoin('job_titles', 'jobseeker_work_experiences.job_title_id', '=', 'job_titles.id')
                ->select('months_of_expereince', 'office_name', 'office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email', 'job_titles.jobtitle_name')
                ->get()
                ->toArray();
        }
        return $work;
    }

}
