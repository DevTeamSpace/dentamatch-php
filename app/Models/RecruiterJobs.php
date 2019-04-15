<?php

namespace App\Models;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\RecruiterJobs
 *
 * @property int $id
 * @property int $job_template_id
 * @property int $recruiter_office_id
 * @property int $preferred_job_location_id
 * @property int $job_type '1'=>Full time,'2'=>Part time,'3'=>Temp
 * @property int|null $no_of_jobs
 * @property int|null $is_monday
 * @property int|null $is_tuesday
 * @property int|null $is_wednesday
 * @property int|null $is_thursday
 * @property int|null $is_friday
 * @property int|null $is_saturday
 * @property int|null $is_sunday
 * @property int|null $is_published
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at                SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @property int $pay_rate
 * @property JobTemplates $jobTemplate
 * @property RecruiterOffice $recruiterOffice
 * @property PreferredJobLocation $preferredLocation
 * @property-read Collection|TempJobDates[] $tempJobActiveDates
 * @property-read Collection|TempJobDates[] $tempJobDates
 * @property-read Collection|JobseekerTempHired[] $hiredDates
 * @property-read Collection|JobLists[] $applications
 *
 * @method static Builder|RecruiterJobs newModelQuery()
 * @method static Builder|RecruiterJobs newQuery()
 * @method static Builder|RecruiterJobs query()
 * @method static Builder|RecruiterJobs whereCreatedAt($value)
 * @method static Builder|RecruiterJobs whereDeletedAt($value)
 * @method static Builder|RecruiterJobs whereId($value)
 * @method static Builder|RecruiterJobs whereIsFriday($value)
 * @method static Builder|RecruiterJobs whereIsMonday($value)
 * @method static Builder|RecruiterJobs whereIsPublished($value)
 * @method static Builder|RecruiterJobs whereIsSaturday($value)
 * @method static Builder|RecruiterJobs whereIsSunday($value)
 * @method static Builder|RecruiterJobs whereIsThursday($value)
 * @method static Builder|RecruiterJobs whereIsTuesday($value)
 * @method static Builder|RecruiterJobs whereIsWednesday($value)
 * @method static Builder|RecruiterJobs whereJobTemplateId($value)
 * @method static Builder|RecruiterJobs whereJobType($value)
 * @method static Builder|RecruiterJobs whereNoOfJobs($value)
 * @method static Builder|RecruiterJobs wherePayRate($value)
 * @method static Builder|RecruiterJobs wherePreferredJobLocationId($value)
 * @method static Builder|RecruiterJobs whereRecruiterOfficeId($value)
 * @method static Builder|RecruiterJobs whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecruiterJobs extends Model
{
    const LIMIT = 10;

    protected $maps = [
        'jobTemplateId'     => 'job_template_id',
        'recuriterOfficeId' => 'recruiter_office_id',
        'jobType'           => 'job_type',
        'noOfJobs'          => 'no_of_jobs',
        'isMonday'          => 'is_monday',
        'isTuesday'         => 'is_tuesday',
        'isWednesday'       => 'is_wednesday',
        'isThursday'        => 'is_thursday',
        'isFriday'          => 'is_friday',
        'isSaturday'        => 'is_saturday',
        'isSunday'          => 'is_sunday',
        'isPublished'       => 'is_published',
    ];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $fillable = ['jobTemplateId', 'recuriterOfficeId', 'jobType', 'noOfJobs', 'isMonday',
                           'isTuesday', 'isWednesday', 'isThursday', 'isFriday', 'isSaturday', 'isSunday', 'isPublished'];

    protected $dates = ['deleted_at'];

    public function tempJobDates()
    {
        return $this->hasMany(TempJobDates::class, 'recruiter_job_id');
    }

    public function tempJobActiveDates()
    {
        return $this->hasMany(TempJobDates::class, 'recruiter_job_id')->select('job_date'); // todo replace with 🠙🠙🠙
    }

    public function hiredDates()
    {
        return $this->hasMany(JobseekerTempHired::class, 'job_id');
    }

    public function jobTemplate()
    {
        return $this->belongsTo(JobTemplates::class);
    }

    public function recruiterOffice()
    {
        return $this->belongsTo(RecruiterOffice::class);
    }

    public function preferredJobLocation()
    {
        return $this->belongsTo(PreferredJobLocation::class);
    }

    public function applications()
    {
        return $this->hasMany(JobLists::class, 'recruiter_job_id');
    }

    public static function searchJob($reqData)
    {
        $jobseekerSkills = JobSeekerSkills::fetchJobseekerSkills($reqData['userId']);

        $userSavedJobs = SavedJobs::select('recruiter_job_id')
            ->where('seeker_id', $reqData['userId'])->get()->pluck('recruiter_job_id')->toArray();


        $rejectedJobsArray = JobLists::where('seeker_id', $reqData['userId'])
            ->whereIn('applied_status', [JobAppliedStatus::REJECTED, JobAppliedStatus::HIRED, JobAppliedStatus::APPLIED, JobAppliedStatus::SHORTLISTED])
            ->get()->pluck('recruiter_job_id')->toArray();

        if (empty($reqData['jobTitle'])) {
            $reqData['jobTitle'] = array_pluck(JobTitles::getAll(), 'id');
        } else {
            $parentIds = JobTitles::active()->whereIn('id', $reqData['jobTitle'])->whereNotNull('parent_id')->distinct()->pluck('parent_id')->toArray();
            $reqData['jobTitle'] = array_merge($reqData['jobTitle'], $parentIds);
        }

        $searchQueryObj = RecruiterJobs::leftJoin('job_lists', function ($query) use ($reqData) {
            $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->where('job_lists.seeker_id', '=', $reqData['userId'])
                ->whereNotIn('job_lists.applied_status', [JobAppliedStatus::REJECTED]);
        })
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->where('recruiter_profiles.is_subscribed', '=', 1)
            ->whereIn('job_templates.job_title_id', $reqData['jobTitle']);

        $searchQueryObj->join('template_skills', function ($query) use ($jobseekerSkills) {
            $query->on('template_skills.job_template_id', '=', 'recruiter_jobs.job_template_id')
                ->whereIn('template_skills.skill_id', $jobseekerSkills);
        });

        $searchQueryObj->join('template_skills as tmp_skills', function ($query) {
            $query->on('tmp_skills.job_template_id', '=', 'recruiter_jobs.job_template_id');
        });

        if (count($rejectedJobsArray) > 0) {
            $searchQueryObj->whereNotIn('recruiter_jobs.id', $rejectedJobsArray);
        }

        $blockedRecruiterModel = ChatUserLists::getBlockedRecruiters($reqData['userId']);
        if (!empty($blockedRecruiterModel)) {
            $blockedRecruiterIds = array_pluck($blockedRecruiterModel, 'recruiter_id');
            $searchQueryObj->whereNotIn('recruiter_profiles.user_id', $blockedRecruiterIds);
        }
        if (isset($reqData['isFulltime']) || isset($reqData['isParttime'])) {
            if ($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 0) {
                $searchQueryObj->where('recruiter_jobs.job_type', JobType::FULLTIME);
            }

            if ($reqData['isFulltime'] == 0 && $reqData['isParttime'] == 1) {
                $searchQueryObj->where('recruiter_jobs.job_type', JobType::PARTTIME);
                $searchQueryObj->where(function ($query) use ($reqData) {
                    if (is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0) {
                        foreach ($reqData['parttimeDays'] as $day) {
                            $query->orWhere('is_' . $day, 1);
                        }
                    }
                });
            }
            $searchQueryObj->where(function ($query) use ($reqData) {
                if ($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 1) {
                    $query->where('recruiter_jobs.job_type', JobType::FULLTIME);
                    if (is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0) {
                        $query->orWhere(function ($query1) use ($reqData) {
                            $query1->where('recruiter_jobs.job_type', JobType::PARTTIME);
                            $query1->where(function ($query2) use ($reqData) {
                                foreach ($reqData['parttimeDays'] as $day) {
                                    $query2->orWhere('is_' . $day, 1);
                                }
                            });
                        });
                    }
                }
            });
        }

        if (!empty($reqData['preferredJobLocationId']) && is_array($reqData['preferredJobLocationId'])) {
            $searchQueryObj->whereIn('recruiter_jobs.preferred_job_location_id', $reqData['preferredJobLocationId']);
        }

        $searchQueryObj->whereIn('recruiter_jobs.job_type', [JobType::FULLTIME, JobType::PARTTIME]);
        $searchQueryObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday',
            'recruiter_jobs.is_thursday', 'recruiter_jobs.is_friday',
            'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
            'job_titles.jobtitle_name', 'recruiter_profiles.office_name', 'job_templates.job_title_id',
            'recruiter_offices.address', 'recruiter_offices.zipcode',
            'recruiter_offices.latitude', 'recruiter_offices.longitude', 'recruiter_jobs.created_at',
            'recruiter_jobs.preferred_job_location_id', DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        $searchQueryObj->addSelect(DB::raw("count(distinct(template_skills.skill_id)) AS matched_skills"));
        $searchQueryObj->addSelect(DB::raw("count(distinct(tmp_skills.skill_id)) AS template_skills_count"));
        $searchQueryObj->addSelect(DB::raw("IF(count(distinct(template_skills.skill_id))>0, (count(distinct(template_skills.skill_id))/count(distinct(tmp_skills.skill_id)))*100,0) AS percentaSkillsMatch"));

        $total = $searchQueryObj->distinct('recruiter_jobs.id')->count('recruiter_jobs.id');
        $page = $reqData['page'];
        $limit = RecruiterJobs::LIMIT;
        $skip = 0;
        if ($page > 1) {
            $skip = ($page - 1) * $limit;
        }
        $foundJobs = $searchQueryObj->groupby('recruiter_jobs.id')->distinct('recruiter_jobs.id')->skip($skip)->take($limit)->orderBy('percentaSkillsMatch', 'desc')->get()->toArray();
        $result = [];
        $jobsList = [];
        foreach ($foundJobs as $value) {
            $isSaved = 0;
            if ((count($userSavedJobs) > 0) && (in_array($value['id'], $userSavedJobs))) {
                $isSaved = 1;
            }
            $value['isSaved'] = $isSaved;
            $jobsList[] = $value;
        }
        $result['list'] = $jobsList;

        $result['total'] = $total;
        return $result;
    }

    public static function getJobDetail($jobId, $userId, $lat = "", $lng = "")
    {
        $tempJob = [];
        $searchResult = [];

        $jobseekerSkills = JobSeekerSkills::fetchJobseekerSkills($userId);

        $userProfile = UserProfile::where('user_id', $userId)->first();
        $longitude = !empty($lng) ? $lng : $userProfile->longitude;
        $latitude = !empty($lat) ? $lat : $userProfile->latitude;
        $searchQueryObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
            ->leftjoin('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
            ->where('recruiter_jobs.id', $jobId);

        $searchQueryObj->join('template_skills', function ($query) use ($jobseekerSkills) {
            $query->on('template_skills.job_template_id', '=', 'recruiter_jobs.job_template_id')
                ->whereIn('template_skills.skill_id', $jobseekerSkills);
        });

        $searchQueryObj->join('template_skills as tmp_skills', function ($query) {
            $query->on('tmp_skills.job_template_id', '=', 'recruiter_jobs.job_template_id');
        });

        $searchQueryObj->select('recruiter_jobs.id', 'recruiter_jobs.no_of_jobs', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday',
            'recruiter_jobs.is_thursday', 'recruiter_jobs.is_friday',
            'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday', 'recruiter_jobs.pay_rate',
            'job_templates.template_name', 'job_templates.template_desc',
            'recruiter_offices.work_everyday_start', 'recruiter_offices.work_everyday_end',
            'recruiter_offices.monday_start', 'recruiter_offices.monday_end',
            'recruiter_offices.tuesday_start', 'recruiter_offices.tuesday_end',
            'recruiter_offices.wednesday_start', 'recruiter_offices.wednesday_end',
            'recruiter_offices.thursday_start', 'recruiter_offices.thursday_end',
            'recruiter_offices.friday_start', 'recruiter_offices.friday_end',
            'recruiter_offices.saturday_start', 'recruiter_offices.saturday_end',
            'recruiter_offices.sunday_start', 'recruiter_offices.sunday_end',
            'job_titles.jobtitle_name', 'recruiter_profiles.office_name', 'recruiter_profiles.office_desc',
            'recruiter_offices.zipcode', 'recruiter_offices.user_id as recruiter_id',
            'recruiter_offices.latitude', 'recruiter_offices.longitude', 'recruiter_jobs.created_at',
            DB::raw("IFNULL(TRIM(LEADING ', ' FROM CONCAT(address_second_line, ', ', address)), address) as address"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS job_posted_time_gap"),
            DB::raw("GROUP_CONCAT(DISTINCT(office_types.officetype_name) SEPARATOR ', ') AS office_type_name")
        );

        $searchQueryObj->addSelect(DB::raw("count(distinct(template_skills.skill_id)) AS matched_skills"));
        $searchQueryObj->addSelect(DB::raw("count(distinct(tmp_skills.skill_id)) AS template_skills_count"));
        $searchQueryObj->addSelect(DB::raw("IF(count(distinct(template_skills.skill_id))>0, (count(distinct(template_skills.skill_id))/count(distinct(tmp_skills.skill_id)))*100,0) AS percentaSkillsMatch"));

        $data = $searchQueryObj->first();
        if ($data->id != null) {
            $tempDates = $data->tempJobActiveDates;
            if (!empty($tempDates)) {
                $searchResult['job_type_dates'] = [];
                foreach ($tempDates as $value) {
                    $tempJob[] = $value->job_date;
                }
            }

            $searchResult = $searchQueryObj->first()->toArray();
        }

        if (!empty($searchResult)) {
            $searchResult['job_type_string'] = JobType::ToString($searchResult['job_type']);
            $searchResult['job_type_dates'] = $tempJob;
        }

        return $searchResult;
    }


    public static function getJobs($limit = "", $tempRating = false)
    {
        $paginationLimit = RecruiterJobs::LIMIT;
        if (!empty($limit)) {
            $paginationLimit = $limit;
        }

        $tempPrevious = RecruiterJobs::chkTempJObRatingPending();
        $excludeJob = $ratingPendingJob = [];
        if ($tempPrevious) {
            $tempPreviousArray = $tempPrevious->toArray();
            foreach ($tempPreviousArray as $previousTempJob) {
                if ($previousTempJob['total_hired'] == $previousTempJob['total_rating']) {
                    $excludeJob[] = $previousTempJob['id'];
                } else {
                    $ratingPendingJob[] = $previousTempJob['id'];
                }
            }
        }

        $jobObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('office_types', 'office_types.id', 'recruiter_office_types.office_type_id')
            ->join('job_templates', function ($query) {
                $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->where('job_templates.user_id', Auth::user()->id);
            })
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id');

        if (is_array($excludeJob) && count($excludeJob) > 0) {
            $jobObj->whereNotIn('recruiter_jobs.id', $excludeJob);
        }

        if ($tempRating == true && count($ratingPendingJob) > 0) {
            $jobObj->whereIn('recruiter_jobs.id', $ratingPendingJob);
        }

        $jobObj->leftJoin('temp_job_dates', function ($query) {
            $query->on('temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id');
        })
            ->leftJoin('job_lists', function ($query) {
                $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                    ->whereIn('job_lists.applied_status', [JobAppliedStatus::INVITED, JobAppliedStatus::APPLIED]);
            })
            ->groupBy('recruiter_jobs.id', 'recruiter_profiles.office_name', 'recruiter_profiles.office_desc');
        $jobObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday', 'recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday', 'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs', 'recruiter_jobs.created_at',
            'recruiter_profiles.office_name', 'recruiter_profiles.office_desc',
            'recruiter_offices.address', 'recruiter_offices.zipcode',
            'job_templates.template_name', 'job_templates.template_desc', 'job_templates.job_title_id',
            'job_titles.jobtitle_name', 'recruiter_jobs.pay_rate',
            DB::raw("group_concat(distinct concat(job_lists.seeker_id,'_', job_lists.applied_status)) AS applied_status"),
            DB::raw("group_concat(distinct concat(office_types.officetype_name)) AS office_types_name"),
            DB::raw("group_concat(distinct(temp_job_dates.job_date) ORDER BY temp_job_dates.job_date ASC) AS temp_job_dates"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"))
            ->orderBy('recruiter_jobs.id', 'desc');

        return $jobObj->paginate($paginationLimit);

    }

    public static function getRecruiterJobDetails($jobId)
    {
        $jobObj = RecruiterJobs::where('recruiter_jobs.id', $jobId)
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
            ->join('job_templates', function ($query) {
                $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->where('job_templates.user_id', Auth::user()->id);
            })
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->leftJoin('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->leftJoin('template_skills', 'job_templates.id', '=', 'template_skills.job_template_id')
            ->groupBy('recruiter_jobs.id', 'recruiter_profiles.office_name', 'recruiter_profiles.office_desc', 'office_types.officetype_name', 'template_skills.job_template_id')
            ->select('recruiter_jobs.id', 'recruiter_jobs.recruiter_office_id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
                'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday', 'recruiter_jobs.is_thursday',
                'recruiter_jobs.is_friday', 'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
                'recruiter_jobs.no_of_jobs', 'recruiter_jobs.created_at', 'recruiter_jobs.job_template_id',
                'recruiter_profiles.office_name', 'recruiter_profiles.office_desc',
                'recruiter_offices.address', 'recruiter_offices.zipcode', 'recruiter_offices.latitude', 'recruiter_offices.longitude',
                'job_templates.template_name', 'job_templates.template_desc', 'job_templates.job_title_id',
                'job_titles.jobtitle_name', 'preferred_job_location_id', 'recruiter_jobs.pay_rate',
                DB::raw("group_concat(distinct(office_types.officetype_name)) AS officetype_name"),
                DB::raw("group_concat(distinct(temp_job_dates.job_date) ORDER BY temp_job_dates.job_date ASC) AS temp_job_dates"),
                DB::raw("group_concat(distinct(template_skills.skill_id)) AS required_skills"),
                DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        return $jobObj->first()->toArray();
    }

    public static function getMatchingSkills($jobId, $userId)
    {
        $return = ['data' => [], 'totalSkills' => 0, 'matchedSkills' => 0, 'percentSkills' => 0];
        $totalSkills = 0;
        $matchedSkills = 0;
        $jobObj = RecruiterJobs::where('recruiter_jobs.id', $jobId)
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('template_skills', 'job_templates.id', '=', 'template_skills.job_template_id')
            ->join('skills', 'skills.id', '=', 'template_skills.skill_id')
            ->join('skills as sk', 'sk.id', '=', 'skills.parent_id')
            ->leftjoin('jobseeker_skills', function ($query) use ($userId) {
                $query->on('jobseeker_skills.skill_id', 'template_skills.skill_id')
                    ->where('jobseeker_skills.user_id', $userId);
            })
            ->where('skills.is_active', Skills::ACTIVE)
            ->where('skills.parent_id', '<>', null)
            ->select('sk.skill_name as parent_skill_name', 'skills.skill_name',
                DB::raw("IF(jobseeker_skills.skill_id = template_skills.skill_id, 1,0) as matchedFlag"))
            ->orderBy('skills.parent_id', 'asc')
            ->orderBy('skills.id', 'desc')
            ->get()
            ->toArray();

        if (!empty($jobObj)) {
            foreach ($jobObj as $value) {
                $totalSkills = $totalSkills + 1;
                if ($value['matchedFlag'] == 1) {
                    $matchedSkills += 1;
                }
                $return['data'][$value['parent_skill_name']][] = $value;
            }
            $return['totalSkills'] = $totalSkills;
            $return['matchedSkills'] = $matchedSkills;
            $return['percentSkills'] = ($totalSkills > 0 ? ($matchedSkills / $totalSkills) * 100 : 0.00);

        }
        return $return;
    }

    public static function getAllTempJobs()
    {
        $jobObj = RecruiterJobs::where('recruiter_jobs.job_type', JobType::TEMPORARY)
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
            ->leftjoin('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
            ->join('job_templates', function ($query) {
                $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->where('job_templates.user_id', Auth::user()->id);
            })
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id');

        $jobObj->leftJoin('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->leftJoin('job_lists', function ($query) {
                $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                    ->whereIn('job_lists.applied_status', [JobAppliedStatus::HIRED]);
            })
            ->groupBy('recruiter_jobs.id', 'recruiter_profiles.office_name', 'recruiter_profiles.office_desc');
        $jobObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday', 'recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday', 'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs', 'recruiter_jobs.created_at',
            'recruiter_profiles.office_name', 'recruiter_profiles.office_desc',
            'recruiter_offices.address', 'recruiter_offices.zipcode',
            'job_templates.template_name', 'job_templates.template_desc', 'job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(job_lists.applied_status) AS applied_status"),
            DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"),
            DB::raw("GROUP_CONCAT(DISTINCT(office_types.officetype_name)) AS office_type_name"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        return $jobObj->get();
    }

    public static function getAllTempJobsHired()
    {
        $jobObj = RecruiterJobs::where('recruiter_jobs.job_type', JobType::TEMPORARY)
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
            ->leftjoin('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
            ->join('job_templates', function ($query) {
                $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->where('job_templates.user_id', Auth::user()->id);
            })
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id');

        $jobObj->leftJoin('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->leftJoin('job_lists', function ($query) {
                $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                    ->whereIn('job_lists.applied_status', [JobAppliedStatus::HIRED]);
            })
            ->groupBy('recruiter_jobs.id', 'recruiter_profiles.office_name', 'recruiter_profiles.office_desc');
        $jobObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday', 'recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday', 'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs', 'recruiter_jobs.created_at',
            'recruiter_profiles.office_name', 'recruiter_profiles.office_desc',
            'recruiter_offices.address', 'recruiter_offices.zipcode',
            'job_templates.template_name', 'job_templates.template_desc', 'job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(job_lists.applied_status) AS applied_status"),
            DB::raw("group_concat(distinct(temp_job_dates.job_date)) AS temp_job_dates"),
            DB::raw("GROUP_CONCAT(DISTINCT(office_types.officetype_name)) AS office_type_name"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        return $jobObj->get();
    }

    public static function getDashboardCalendarData($start)
    {
        $jobData = RecruiterJobs::where('recruiter_jobs.job_type', JobType::TEMPORARY)
            ->join('job_templates', function ($query) {
                $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->where('job_templates.user_id', Auth::user()->id);
            })
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->leftJoin('job_lists', function ($query) {
                $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                    ->where('job_lists.applied_status', JobAppliedStatus::HIRED);
            })
            ->leftjoin('jobseeker_temp_hired', 'jobseeker_temp_hired.job_id', '=', 'recruiter_jobs.id')
            ->leftjoin('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'jobseeker_temp_hired.jobseeker_id')
            ->whereBetween('temp_job_dates.job_date', [(date('Y-m-d', $start)), (date('Y-m-d', strtotime('next sunday', $start)))])
            ->select('jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic',
                'job_titles.jobtitle_name', 'jobseeker_temp_hired.jobseeker_id', 'jobseeker_temp_hired.job_id',
                DB::raw("group_concat(job_lists.applied_status) AS applied_status"),
                DB::raw("group_concat(distinct(temp_job_dates.job_date)) AS temp_job_dates"),
                'jobseeker_temp_hired.job_date')
            ->groupBy('recruiter_jobs.id', 'temp_job_dates.job_date')
            ->orderBy('temp_job_dates.job_date', 'asc')
            ->get();

        $resData = [];
        foreach ($jobData as $job) {
            $profilePic = '';
            if ($job->profile_pic != '') {
                $profilePic = url("image/" . 60 . "/" . 60 . "/?src=" . $job->profile_pic);
            }

            if (!isset($resData[$job->temp_job_dates])) {
                $resData[$job->temp_job_dates] = ['jobTitle' => $job->jobtitle_name, 'jobCount' => 1,
                                                  'jobId'    => $job->job_id, 'seekerData' => []];
                if ($job->job_id != '') {
                    $resData[$job->temp_job_dates]['seekerData'] = [['profile_pic' => $profilePic]];
                }
            } else {
                if ($resData[$job->temp_job_dates]['jobId'] == $job->job_id && $job->job_id != '') {
                    array_push($resData[$job->temp_job_dates]['seekerData'], ['profile_pic' => $profilePic]);
                } else {
                    $resData[$job->temp_job_dates]['jobCount']++;
                }
            }
        }
        return $resData;
    }

    public static function getDashboardCalendar($dashboard = false)
    {
        $jobObj = RecruiterJobs::where('recruiter_jobs.job_type', JobType::TEMPORARY)
            ->join('job_templates', function ($query) {
                $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->where('job_templates.user_id', Auth::user()->id);
            })
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id');

        $jobObj->leftJoin('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->leftJoin('job_lists', function ($query) {
                $query->on('job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                    ->whereIn('job_lists.applied_status', [JobAppliedStatus::HIRED]);
            });

        if (!empty($dashboard)) {
            $ts = strtotime(date("Y-m-d"));
            $start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
            $jobObj->whereBetween('temp_job_dates.job_date', [(date('Y-m-d', $start)), (date('Y-m-d', strtotime('next sunday', $start)))]);
        }
        $jobObj->groupBy('recruiter_jobs.id', 'temp_job_dates.job_date');
        $jobObj->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday', 'recruiter_jobs.is_wednesday', 'recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday', 'recruiter_jobs.is_saturday', 'recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs', 'recruiter_jobs.created_at',
            'job_templates.template_name', 'job_templates.template_desc', 'job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(job_lists.applied_status) AS applied_status"),
            DB::raw("group_concat(distinct(temp_job_dates.job_date)) AS temp_job_dates"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        return $jobObj->orderBy('temp_job_dates.job_date', 'desc')->get();
    }

    public static function getTempJobsReports($history = false)
    {
        $jobs = RecruiterJobs::where(['recruiter_jobs.job_type' => JobType::TEMPORARY, 'recruiter_offices.user_id' => Auth::user()->id])
            ->join('recruiter_offices', 'recruiter_offices.id', '=', 'recruiter_jobs.recruiter_office_id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->groupBy('recruiter_jobs.id');
        $jobs->select('recruiter_jobs.id as job_title_id', 'job_titles.jobtitle_name', 'recruiter_jobs.pay_rate',
            DB::raw("recruiter_jobs.no_of_jobs as jobs_count"));

        return $jobs->get();
    }

    public static function getIndividualTempJob($job_title_id, $history = false)
    {
        $jobs = RecruiterJobs::where(['recruiter_jobs.job_type' => JobType::TEMPORARY, 'recruiter_offices.user_id' => Auth::user()->id, 'recruiter_jobs.id' => $job_title_id])
            ->join('recruiter_offices', 'recruiter_offices.id', '=', 'recruiter_jobs.recruiter_office_id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->orderBy('temp_job_dates.job_date', 'desc');
        $jobs->select('temp_job_dates.job_date as job_created_at', 'recruiter_jobs.id as recruiter_job_id', 'recruiter_jobs.job_type');

        return $jobs->get();
    }

    public static function checkPendingTempJobsRating()
    {
        $jobs = RecruiterJobs::select(['recruiter_jobs.id as recruitedJobId', 'job_lists.seeker_id as jobSeekerId', 'job_ratings.seeker_id as ratedJobSeekerId'])
            ->where(['applied_status' => JobAppliedStatus::HIRED, 'recruiter_jobs.job_type' => JobType::TEMPORARY, 'job_templates.user_id' => Auth::user()->id])
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->leftjoin('job_ratings', 'job_ratings.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->distinct();

        $jobSeekerCount = $jobs->get()->count();
        $ratedSeekerCount = $jobs->whereNotNull('job_ratings.seeker_id')->get()->count();
        return ['seekerCount' => $jobSeekerCount, 'ratedSeekerCount' => $ratedSeekerCount];
    }

    public static function chkTempJObRatingPending()
    {

        $jobObj = RecruiterJobs::join('job_templates', function ($query) {
            $query->on('job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->where('job_templates.user_id', Auth::user()->id);
        })
            ->join('temp_job_dates', 'temp_job_dates.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->where('recruiter_jobs.job_type', '=', JobType::TEMPORARY)
            ->join('job_lists', function ($query) {
                $query->on('temp_job_dates.recruiter_job_id', '=', 'job_lists.recruiter_job_id')
                    ->where('job_lists.applied_status', JobAppliedStatus::HIRED);
            })
            ->leftjoin('job_ratings', function ($query) {
                $query->on('temp_job_dates.recruiter_job_id', '=', 'job_ratings.recruiter_job_id');
            })
            ->select('recruiter_jobs.id', 'recruiter_jobs.job_type',
                DB::raw("count(distinct job_lists.seeker_id) as total_hired"),
                DB::raw("count(distinct job_ratings.seeker_id) as total_rating"),
                DB::raw("max(temp_job_dates.job_date) as job_date")
            )
            ->having('job_date', '<', date('Y-m-d'))
            ->groupBy('temp_job_dates.recruiter_job_id')
            ->orderBy('recruiter_jobs.id', 'desc');
        return $jobObj->get();
    }
}
    