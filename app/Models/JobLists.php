<?php

namespace App\Models;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\JobLists
 *
 * @property int $id
 * @property int $recruiter_job_id
 * @property int|null $temp_job_id
 * @property int $seeker_id
 * @property int $applied_status '1'=>Invited,'2'=>Applied,'3'=>Shortlisted,'4'=>Hired,'5'=>Canceled
 * @property string|null $cancel_reason
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TempJobDates[] $tempJobDates
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists newQuery()
 * @method static \Illuminate\Database\Query\Builder|JobLists onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereAppliedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereCancelReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereRecruiterJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereSeekerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereTempJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobLists whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|JobLists withTrashed()
 * @method static \Illuminate\Database\Query\Builder|JobLists withoutTrashed()
 * @mixin \Eloquent
 */
class JobLists extends Model
{
    use SoftDeletes;

    const LIMIT = 10;

    protected $table = 'job_lists';
    protected $primaryKey = 'id';
    protected $fillable = ['recruiter_job_id', 'temp_job_id', 'seeker_id', 'applied_status', 'cancel_reason'];

    public function tempJobDates()
    {
        return $this->hasMany(TempJobDates::class, 'recruiter_job_id', 'recruiter_job_id')->select('job_date', 'recruiter_job_id');
    }

    public static function listJobsByStatus($reqData)
    {
        $jobseekerSkills = JobSeekerSkills::fetchJobseekerSkills($reqData['userId']);

        $searchQueryObj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->where('job_lists.seeker_id', '=', $reqData['userId']);
        if ($reqData['type'] == 2) {
            $searchQueryObj->where('job_lists.applied_status', '=', JobAppliedStatus::APPLIED);
        } else {
            $searchQueryObj->where('job_lists.applied_status', '=', JobAppliedStatus::SHORTLISTED);
        }

        $searchQueryObj->join('template_skills', function ($query) use ($jobseekerSkills) {
            $query->on('template_skills.job_template_id', '=', 'recruiter_jobs.job_template_id')
                ->whereIn('template_skills.skill_id', $jobseekerSkills);
        });

        $searchQueryObj->join('template_skills as tmp_skills', function ($query) {
            $query->on('tmp_skills.job_template_id', '=', 'recruiter_jobs.job_template_id');
        });

        $searchQueryObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type',
            'recruiter_jobs.is_monday', 'recruiter_jobs.is_tuesday',
            'recruiter_jobs.is_wednesday', 'recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday', 'recruiter_jobs.is_saturday',
            'recruiter_jobs.is_sunday', 'job_titles.jobtitle_name',
            'recruiter_profiles.office_name', 'recruiter_offices.address',
            'recruiter_offices.zipcode', 'recruiter_offices.latitude',
            'recruiter_offices.longitude', 'recruiter_jobs.created_at',
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
        $searchResult = $searchQueryObj->groupby('recruiter_jobs.id')
            ->orderby('matched_skills', 'desc')->skip($skip)->take($limit)->get();
        $result = [];
        if ($searchResult) {
            $result['list'] = $searchResult->toArray();
            $result['total'] = $total;
        }
        return $result;
    }

    public static function isJobApplied($jobId, $userId)
    {
        $return = 0;
        $jobExists = static::where('seeker_id', '=', $userId)
            ->where('recruiter_job_id', '=', $jobId)
            /*->where('applied_status', '=', JobLists::APPLIED)*/
            ->orderby('id', 'desc')
            ->first();
        if ($jobExists) {
            $return = $jobExists->applied_status;
        }
        return $return;
    }

    public static function postJobCalendar($userId, $jobStartDate, $jobEndDate)
    {
        $result = [];

        $tempQueryObj = JobseekerTempHired::join('recruiter_jobs', 'recruiter_jobs.id', '=', 'jobseeker_temp_hired.job_id')
            ->join('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->where('jobseeker_temp_hired.jobseeker_id', '=', $userId)
            ->whereNull('job_lists.deleted_at')->whereNull('recruiter_jobs.deleted_at')
            ->where('job_lists.applied_status', '=', JobAppliedStatus::HIRED)
            ->where('recruiter_jobs.job_type', JobType::TEMPORARY)
            ->where('jobseeker_temp_hired.job_date', ">=", $jobStartDate)
            ->where('jobseeker_temp_hired.job_date', "<=", $jobEndDate)
            ->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
                'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday',
                'recruiter_jobs.is_thursday', 'recruiter_jobs.is_friday',
                'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday', 'recruiter_jobs.pay_rate',
                'job_titles.jobtitle_name', 'recruiter_profiles.office_name',
                'recruiter_offices.address', 'recruiter_offices.zipcode',
                'recruiter_offices.latitude', 'recruiter_offices.longitude',
                'recruiter_jobs.created_at as job_created_at', 'job_lists.created_at as job_applied_on',
                DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"),
                DB::raw("DATE_FORMAT(jobseeker_temp_hired.job_date, '%Y-%m-%d') AS tempDates"),
                DB::raw("DATE_FORMAT(job_lists.updated_at, '%Y-%m-%d') AS jobDate")
            )
            ->groupBy('recruiter_jobs.id')
            ->groupBy('jobseeker_temp_hired.jobseeker_id')
            ->groupBy('jobseeker_temp_hired.job_date')
            ->orderBy('jobseeker_temp_hired.job_date')
            ->get();

        if ($tempQueryObj) {
            $searchResults = $tempQueryObj->toArray();
            $currentDate = date('Y-m-d');
            foreach ($searchResults as $key => $value) {
                $searchResults[$key]['job_type_string'] = JobType::ToString($value['job_type']);
                $searchResults[$key]['currentDate'] = $currentDate;
            }

            $result['list'] = $searchResults;
            $result['total'] = count($searchResults);
        }
        return $result;
    }

    public static function getJobSeekerList($job, $forJobType = '')
    {
        $obj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
            ->join('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
            ->where('job_lists.recruiter_job_id', $job['id']);
        if ($forJobType != '') {
            $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::HIRED]);
        } else {
            $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::INVITED, JobAppliedStatus::APPLIED, JobAppliedStatus::SHORTLISTED, JobAppliedStatus::HIRED]);
        }
        $obj->select('job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 'job_lists.seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type');

        if ($job['job_type'] == JobType::FULLTIME) {
            $obj->addSelect('jobseeker_profiles.is_fulltime');
        } else if ($job['job_type'] == JobType::PARTTIME) {
            $obj->addSelect('jobseeker_profiles.is_parttime_monday', 'jobseeker_profiles.is_parttime_tuesday', 'jobseeker_profiles.is_parttime_wednesday', 'jobseeker_profiles.is_parttime_thursday', 'jobseeker_profiles.is_parttime_friday', 'jobseeker_profiles.is_parttime_saturday', 'jobseeker_profiles.is_parttime_sunday');
        } else if ($job['job_type'] == JobType::TEMPORARY) {
            $obj->leftjoin('job_ratings', function ($query) {
                $query->on('job_ratings.seeker_id', '=', 'job_lists.seeker_id')
                    //->where('job_ratings.recruiter_job_id', '=', 'job_lists.recruiter_job_id')
                    ->whereNotNull('job_lists.temp_job_id');
            })
                ->leftjoin('favourites', function ($query) {
                    $query->on('favourites.seeker_id', '=', 'job_lists.seeker_id')
                        ->where('favourites.recruiter_id', Auth::user()->id);
                })
                ->addSelect('favourites.seeker_id as is_favourite')
                ->leftJoin('temp_job_dates', 'job_lists.temp_job_id', '=', 'temp_job_dates.id')
                ->addSelect(DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"))
                ->addSelect(DB::raw("avg(punctuality) as punctuality"), DB::raw("avg(time_management) as time_management"),
                    DB::raw("avg(skills) as skills"), DB::raw("avg(teamwork) as teamwork"), DB::raw("avg(onemore) as onemore"))
                ->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills)+avg(teamwork)+avg(onemore))/5 AS avg_rating"))
                ->groupby('job_lists.applied_status', 'job_lists.seeker_id', 'job_lists.recruiter_job_id');

        }

        $data = $obj->orderby('job_lists.applied_status', 'desc')
            ->get();

        return ($data->groupBy('applied_status')->toArray());
    }

    public static function getJobInfo($seekerId, $jobId)
    {
        return static::where('seeker_id', $seekerId)->where('recruiter_job_id', $jobId)
            ->whereIn('applied_status', [JobAppliedStatus::INVITED, JobAppliedStatus::APPLIED, JobAppliedStatus::SHORTLISTED])->first();
    }

    public static function getJobSeekerStatus($jobId)
    {
        return JobLists::where(['recruiter_job_id' => $jobId])->count();
    }

    public static function getJobSeekerWithRatingList($job, $appliedStatus = '', $forJobType = '')
    {

        $obj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
            ->join('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
            ->leftjoin('chat_user_list', function ($query) {
                $query->on('chat_user_list.recruiter_id', '=', 'recruiter_offices.user_id')
                    ->on('chat_user_list.seeker_id', '=', 'job_lists.seeker_id');
            })
            ->where('job_lists.recruiter_job_id', $job['id']);

        $obj->join('template_skills as tmp_skills', 'tmp_skills.job_template_id', '=', 'recruiter_jobs.job_template_id');
        $obj->join('template_skills', 'template_skills.job_template_id', '=', 'recruiter_jobs.job_template_id');

        $obj->join('jobseeker_skills', function ($query) {
            $query->on('jobseeker_skills.user_id', '=', 'jobseeker_profiles.user_id')
                ->on('jobseeker_skills.skill_id', '=', 'template_skills.skill_id');
        });

        if ($forJobType != '') {
            $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::HIRED]);
        } else {
            if ($appliedStatus == "") {
                $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::INVITED, JobAppliedStatus::APPLIED, JobAppliedStatus::SHORTLISTED, JobAppliedStatus::HIRED]);
            } else {
                if ($appliedStatus == JobAppliedStatus::INVITED) {
                    $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::INVITED]);
                } else if ($appliedStatus == JobAppliedStatus::APPLIED) {
                    $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::APPLIED]);
                } else if ($appliedStatus == JobAppliedStatus::SHORTLISTED) {
                    $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::SHORTLISTED]);
                } else if ($appliedStatus == JobAppliedStatus::HIRED) {
                    $obj->whereIn('job_lists.applied_status', [JobAppliedStatus::HIRED]);
                }
            }
        }
        $obj->select('job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 'job_lists.seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type', 'recruiter_block', 'seeker_block');
        $obj->addSelect(DB::raw("count(distinct(jobseeker_skills.skill_id)) AS matched_skills"));
        $obj->addSelect(DB::raw("count(distinct(tmp_skills.skill_id)) AS template_skills_count"));
        $obj->addSelect(DB::raw("IF(count(distinct(jobseeker_skills.skill_id))>0, (count(distinct(jobseeker_skills.skill_id))/count(distinct(tmp_skills.skill_id)))*100,0) AS percentaSkillsMatch"));

        if ($job['job_type'] == JobType::FULLTIME) {
            $obj->addSelect('jobseeker_profiles.is_fulltime');
        } else if ($job['job_type'] == JobType::PARTTIME) {
            $obj->addSelect('jobseeker_profiles.is_parttime_monday', 'jobseeker_profiles.is_parttime_tuesday', 'jobseeker_profiles.is_parttime_wednesday', 'jobseeker_profiles.is_parttime_thursday', 'jobseeker_profiles.is_parttime_friday', 'jobseeker_profiles.is_parttime_saturday', 'jobseeker_profiles.is_parttime_sunday');
        } else if ($job['job_type'] == JobType::TEMPORARY) {
            $obj->leftjoin('job_ratings', function ($query) {
                $query->on('job_ratings.recruiter_job_id', '=', 'job_lists.recruiter_job_id');
                $query->on('job_ratings.seeker_id', '=', 'job_lists.seeker_id');

            })
                ->leftjoin('jobseeker_temp_hired', function ($query) {
                    $query->on('jobseeker_temp_hired.job_id', '=', 'job_lists.recruiter_job_id');
                    $query->on('jobseeker_temp_hired.jobseeker_id', '=', 'job_lists.seeker_id');
                })
                ->leftjoin('favourites', function ($query) {
                    $query->on('favourites.seeker_id', '=', 'job_lists.seeker_id')
                        ->where('favourites.recruiter_id', Auth::user()->id);
                })
                ->addSelect('job_ratings.seeker_id as ratingId')
                ->addSelect('punctuality')
                ->addSelect('time_management')
                ->addSelect('skills')
                ->addSelect('teamwork')
                ->addSelect('onemore')
                ->addSelect('favourites.seeker_id as is_favourite')
                ->leftJoin('temp_job_dates', 'job_lists.temp_job_id', '=', 'temp_job_dates.id')
                ->addSelect(DB::raw("group_concat(distinct(temp_job_dates.job_date)) AS temp_job_dates"))
                ->addSelect(DB::raw("group_concat(distinct(jobseeker_temp_hired.job_date)) AS hired_job_dates"))
                ->addSelect(DB::raw("avg(punctuality) as avg_punctuality"), DB::raw("avg(time_management) as avg_time_management"),
                    DB::raw("avg(skills) as avg_skills"), DB::raw("avg(teamwork) as avg_teamwork"), DB::raw("avg(onemore) as avg_onemore"))
                ->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills))/3 AS avg_rating"));

        }

        $data = $obj->groupby('job_lists.applied_status', 'job_lists.seeker_id', 'job_lists.recruiter_job_id')
            ->orderby('job_lists.applied_status', 'desc');

        if ($appliedStatus == '') {
            return ($data->groupBy('applied_status')->get()->toArray());
        } else {
            $res = $data->Paginate(JobLists::LIMIT);
            $res->setPath(url('job/details', [$job['id'], $appliedStatus]));

            return $res;
        }
    }
}
