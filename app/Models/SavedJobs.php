<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\SavedJobs
 *
 * @property int $id
 * @property int $recruiter_job_id
 * @property int|null $temp_job_id
 * @property int $seeker_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read User $seeker
 * @property-read RecruiterJobs $job
 *
 * @method static bool|null forceDelete()
 * @method static Builder|SavedJobs newModelQuery()
 * @method static Builder|SavedJobs newQuery()
 * @method static QueryBuilder|SavedJobs onlyTrashed()
 * @method static Builder|SavedJobs query()
 * @method static bool|null restore()
 * @method static Builder|SavedJobs whereCreatedAt($value)
 * @method static Builder|SavedJobs whereDeletedAt($value)
 * @method static Builder|SavedJobs whereId($value)
 * @method static Builder|SavedJobs whereRecruiterJobId($value)
 * @method static Builder|SavedJobs whereSeekerId($value)
 * @method static Builder|SavedJobs whereTempJobId($value)
 * @method static Builder|SavedJobs whereUpdatedAt($value)
 * @method static QueryBuilder|SavedJobs withTrashed()
 * @method static QueryBuilder|SavedJobs withoutTrashed()
 * @mixin \Eloquent
 */
class SavedJobs extends Model
{
    use SoftDeletes;   // todo why using this and forceDelete at the same time?

    protected $table = 'saved_jobs';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'created_at'
    ];

    const LIMIT = 10;

    public function job()
    {
        return $this->belongsTo(RecruiterJobs::class, 'recruiter_job_id');
    }

    public function seeker()
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    public static function listSavedJobs($reqData)
    {
        $jobseekerSkills = JobSeekerSkills::fetchJobseekerSkills($reqData['userId']);

        $searchQueryObj = SavedJobs::join('recruiter_jobs', 'saved_jobs.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->where('saved_jobs.seeker_id', '=', $reqData['userId']);

        $searchQueryObj->join('template_skills', function ($query) use ($jobseekerSkills) {
            $query->on('template_skills.job_template_id', '=', 'recruiter_jobs.job_template_id')
                ->whereIn('template_skills.skill_id', $jobseekerSkills);
        });

        $searchQueryObj->join('template_skills as tmp_skills', function ($query) {
            $query->on('tmp_skills.job_template_id', '=', 'recruiter_jobs.job_template_id');
        });

        $searchQueryObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday',
            'recruiter_jobs.is_thursday', 'recruiter_jobs.is_friday',
            'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
            'job_titles.jobtitle_name', 'recruiter_profiles.office_name',
            'recruiter_offices.address', 'recruiter_offices.zipcode',
            'recruiter_offices.latitude', 'recruiter_offices.longitude', 'recruiter_jobs.created_at',
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        $searchQueryObj->addSelect(DB::raw("count(distinct(template_skills.skill_id)) AS matched_skills"));
        $searchQueryObj->addSelect(DB::raw("count(distinct(tmp_skills.skill_id)) AS template_skills_count"));
        $searchQueryObj->addSelect(DB::raw("IF(count(distinct(template_skills.skill_id))>0, (count(distinct(template_skills.skill_id))/count(distinct(tmp_skills.skill_id)))*100,0) AS percentaSkillsMatch"));

        $total = $searchQueryObj->distinct('recruiter_jobs.id')->count('recruiter_jobs.id');

        $page = $reqData['page'];
        $limit = SavedJobs::LIMIT;
        $skip = 0;
        if ($page > 1) {
            $skip = ($page - 1) * $limit;
        }
        $searchResult = $searchQueryObj->groupby('recruiter_jobs.id')->skip($skip)->take($limit)->get();
        $result = [];
        if ($searchResult) {
            $result['list'] = $searchResult->toArray();
            $result['total'] = $total;
        }
        return $result;
    }

    public static function getJobSavedStatus($jobId, $userId)
    {
        $return = 0;

        $savedJobs = static::where('seeker_id', $userId)->where('recruiter_job_id', $jobId)->first();

        if ($savedJobs) {
            $return = 1;
        }
        return $return;
    }
}
