<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Auth;
use App\Models\TempJobDates;


class JobLists extends Model {

    use SoftDeletes;

    const INVITED = 1;
    const APPLIED = 2;
    const SHORTLISTED = 3;
    const HIRED = 4;
    const REJECTED = 5;
    const CANCELLED = 6;
    const APPLIED_STATUS = [JobLists::INVITED => 'Invited', JobLists::APPLIED => 'Applied',
        JobLists::SHORTLISTED => 'Shortlisted', JobLists::HIRED => 'Hired',
        JobLists::REJECTED => 'Rejected', JobLists::CANCELLED => 'Cancelled'];

    static $jobTypeName = ['1' => 'Full Time', '2' => 'Part Time', '3' => 'Temp'];
    protected $table = 'job_lists';
    protected $primaryKey = 'id';
    protected $fillable = ['recruiter_job_id', 'temp_job_id', 'seeker_id', 'applied_status', 'cancel_reason'];

    const LIMIT = 10;

    public function tempJobDates() {
        return $this->hasMany(TempJobDates::class, 'recruiter_job_id', 'recruiter_job_id')->select('job_date', 'recruiter_job_id');
    }

    public static function listJobsByStatus($reqData) {
        
        $jobseekerSkills = JobSeekerSkills::fetchJobseekerSkills($reqData['userId']);
        
        $searchQueryObj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
                ->where('job_lists.seeker_id', '=', $reqData['userId']);
        if ($reqData['type'] == 2) {
            $searchQueryObj->where('job_lists.applied_status', '=', JobLists::APPLIED);
        } else {
            $searchQueryObj->where('job_lists.applied_status', '=', JobLists::SHORTLISTED);
        }

        $searchQueryObj->join('template_skills',function($query) use ($jobseekerSkills){
                $query->on('template_skills.job_template_id','=','recruiter_jobs.job_template_id')
                        ->whereIn('template_skills.skill_id',$jobseekerSkills);
                });
        
        $searchQueryObj->join('template_skills as tmp_skills',function($query) {
                $query->on('tmp_skills.job_template_id','=','recruiter_jobs.job_template_id');
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
        $searchResult = $searchQueryObj->groupby('recruiter_jobs.id')->skip($skip)->take($limit)->get();
        $result = array();
        if ($searchResult) {
            $result['list'] = $searchResult->toArray();
            $result['total'] = $total;
        }
        return $result;
    }

    public static function isJobApplied($jobId, $userId) {
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

        $tempQueryObj = JobseekerTempHired::join('recruiter_jobs', 'recruiter_jobs.id', '=','jobseeker_temp_hired.job_id')
                        ->join('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                        ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->where('jobseeker_temp_hired.jobseeker_id','=' ,$userId)
                        ->where('job_lists.applied_status', '=' , JobLists::HIRED)
                        ->where('recruiter_jobs.job_type', RecruiterJobs::TEMPORARY)
                        ->where('jobseeker_temp_hired.job_date', ">=",$jobStartDate)
                        ->where('jobseeker_temp_hired.job_date', "<=",$jobEndDate)
                        ->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
                            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday',
                            'recruiter_jobs.is_thursday','recruiter_jobs.is_friday',
                            'recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
                            'job_titles.jobtitle_name','recruiter_profiles.office_name',
                            'recruiter_offices.address','recruiter_offices.zipcode',
                            'recruiter_offices.latitude','recruiter_offices.longitude',
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
            foreach ($searchResults as $key=>$value) {
                $searchResults[$key]['job_type_string'] = static::$jobTypeName[$value['job_type']];
            }
            
            $result['list'] = $searchResults;
            $result['total'] = count($searchResults);
        }
        return $result;
    }

    public static function getJobSeekerList($job, $forJobType='') {
        $obj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                ->join('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
                ->where('job_lists.recruiter_job_id', $job['id']);
        if($forJobType!=''){
            $obj->whereIn('job_lists.applied_status', [JobLists::HIRED]);
        }else{
            $obj->whereIn('job_lists.applied_status', [JobLists::INVITED, JobLists::APPLIED, JobLists::SHORTLISTED, JobLists::HIRED]);
        }
        $obj->select('job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 'job_lists.seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type');

        if ($job['job_type'] == RecruiterJobs::FULLTIME) {
            $obj->addSelect('jobseeker_profiles.is_fulltime');
        } elseif ($job['job_type'] == RecruiterJobs::PARTTIME) {
            $obj->addSelect('jobseeker_profiles.is_parttime_monday', 'jobseeker_profiles.is_parttime_tuesday', 'jobseeker_profiles.is_parttime_wednesday', 'jobseeker_profiles.is_parttime_thursday', 'jobseeker_profiles.is_parttime_friday', 'jobseeker_profiles.is_parttime_saturday', 'jobseeker_profiles.is_parttime_sunday');
        } elseif ($job['job_type'] == RecruiterJobs::TEMPORARY) {
            $obj->leftjoin('job_ratings', function($query) {
                        $query->on('job_ratings.seeker_id', '=', 'job_lists.seeker_id')
                        //->where('job_ratings.recruiter_job_id', '=', 'job_lists.recruiter_job_id')
                        ->whereNotNull('job_lists.temp_job_id');
                })
                ->leftjoin('favourites',function($query){
                    $query->on('favourites.seeker_id','=','job_lists.seeker_id')
                        ->where('favourites.recruiter_id',Auth::user()->id);
                })
                ->addSelect('favourites.seeker_id as is_favourite')
                ->leftJoin('temp_job_dates','job_lists.temp_job_id','=','temp_job_dates.id')
                ->addSelect(DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"))
                ->addSelect(DB::raw("avg(punctuality) as punctuality"),DB::raw("avg(time_management) as time_management"),
                        DB::raw("avg(skills) as skills"),DB::raw("avg(teamwork) as teamwork"),DB::raw("avg(onemore) as onemore"))
                ->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills)+avg(teamwork)+avg(onemore))/5 AS avg_rating"))
                ->groupby('job_lists.applied_status','job_lists.seeker_id','job_lists.recruiter_job_id');
            /*$obj->leftjoin('jobseeker_temp_availability',function($query) use ($job){
                $query->on('jobseeker_temp_availability.user_id', '=', 'job_lists.seeker_id')
                ->whereIn('jobseeker_temp_availability.temp_job_date',explode(',',$job->temp_job_dates));
            })
            ->groupby('job_lists.applied_status','job_lists.seeker_id');
            $obj->addSelect(DB::raw("group_concat(jobseeker_temp_availability.temp_job_date) AS temp_job_dates"));*/
        }

        $data = $obj->orderby('job_lists.applied_status', 'desc')
                ->get();

        return ($data->groupBy('applied_status')->toArray());
    }
    
    public static function getJobInfo($seekerId,$jobId) {
        return static::where('seeker_id',$seekerId)->where('recruiter_job_id',$jobId)
                ->whereIn('applied_status',[JobLists::INVITED,  JobLists::APPLIED,  JobLists::SHORTLISTED])->first();
    }
    
    public static function getJobSeekerStatus($jobId){
        return JobLists::where(['recruiter_job_id' => $jobId])->count();
    }
    
    public static function getJobSeekerWithRatingList($job,$appliedStatus='',$forJobType='') {
        
        $obj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                ->join('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
                ->leftjoin('chat_user_list', 'chat_user_list.recruiter_id', '=', 'recruiter_offices.user_id')
                ->where('job_lists.recruiter_job_id', $job['id']);
        if($forJobType!=''){
            $obj->whereIn('job_lists.applied_status', [JobLists::HIRED]);
        }else{
            if($appliedStatus == ""){
               $obj->whereIn('job_lists.applied_status', [JobLists::INVITED, JobLists::APPLIED, JobLists::SHORTLISTED, JobLists::HIRED]); 
            }else{
                if($appliedStatus == JobLists::INVITED){
                    $obj->whereIn('job_lists.applied_status', [JobLists::INVITED]);
                }else if($appliedStatus == JobLists::APPLIED){
                    $obj->whereIn('job_lists.applied_status', [JobLists::APPLIED]);
                }else if($appliedStatus == JobLists::SHORTLISTED){
                    $obj->whereIn('job_lists.applied_status', [JobLists::SHORTLISTED]);
                }else if($appliedStatus == JobLists::HIRED){
                    $obj->whereIn('job_lists.applied_status', [JobLists::HIRED]);
                }
            }
        }
        $obj->select('job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 'job_lists.seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type', 'recruiter_block', 'seeker_block');

        if ($job['job_type'] == RecruiterJobs::FULLTIME) {
            $obj->addSelect('jobseeker_profiles.is_fulltime');
        } elseif ($job['job_type'] == RecruiterJobs::PARTTIME) {
            $obj->addSelect('jobseeker_profiles.is_parttime_monday', 'jobseeker_profiles.is_parttime_tuesday', 'jobseeker_profiles.is_parttime_wednesday', 'jobseeker_profiles.is_parttime_thursday', 'jobseeker_profiles.is_parttime_friday', 'jobseeker_profiles.is_parttime_saturday', 'jobseeker_profiles.is_parttime_sunday');
        } elseif ($job['job_type'] == RecruiterJobs::TEMPORARY) {
            $obj->leftjoin('job_ratings', function($query) {
                        $query->on('job_ratings.recruiter_job_id', '=', 'job_lists.recruiter_job_id');
                        $query->on('job_ratings.seeker_id', '=', 'job_lists.seeker_id');
                        
                })
                ->leftjoin('jobseeker_temp_hired', function($query) {
                        $query->on('jobseeker_temp_hired.job_id', '=', 'job_lists.recruiter_job_id');
                        $query->on('jobseeker_temp_hired.jobseeker_id', '=', 'job_lists.seeker_id');
                })
                ->leftjoin('favourites',function($query){
                    $query->on('favourites.seeker_id','=','job_lists.seeker_id')
                        ->where('favourites.recruiter_id',Auth::user()->id);
                })
                ->addSelect('job_ratings.seeker_id as ratingId')
                ->addSelect('punctuality')
                ->addSelect('time_management')
                ->addSelect('skills')
                ->addSelect('teamwork')
                ->addSelect('onemore')
                ->addSelect('favourites.seeker_id as is_favourite')
                ->leftJoin('temp_job_dates','job_lists.temp_job_id','=','temp_job_dates.id')
                ->addSelect(DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"))
                ->addSelect(DB::raw("group_concat(jobseeker_temp_hired.job_date) AS hired_job_dates"))
                ->addSelect(DB::raw("avg(punctuality) as avg_punctuality"),DB::raw("avg(time_management) as avg_time_management"),
                        DB::raw("avg(skills) as avg_skills"),DB::raw("avg(teamwork) as avg_teamwork"),DB::raw("avg(onemore) as avg_onemore"))
                ->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills))/3 AS avg_rating"))
                //->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills)+avg(teamwork)+avg(onemore))/5 AS avg_rating"))
                ->groupby('job_lists.applied_status','job_lists.seeker_id','job_lists.recruiter_job_id');
            /*$obj->leftjoin('jobseeker_temp_availability',function($query) use ($job){
                $query->on('jobseeker_temp_availability.user_id', '=', 'job_lists.seeker_id')
                ->whereIn('jobseeker_temp_availability.temp_job_date',explode(',',$job->temp_job_dates));
            })
            ->groupby('job_lists.applied_status','job_lists.seeker_id');
            $obj->addSelect(DB::raw("group_concat(jobseeker_temp_availability.temp_job_date) AS temp_job_dates"));*/
        }

        $data = $obj->orderby('job_lists.applied_status', 'desc');
        
        if($appliedStatus == ''){
            return ($data->groupBy('applied_status')->get()->toArray());
        }else{
            $res = $data->Paginate(JobLists::LIMIT);
            $res->setPath(url('job/details',[$job['id'],$appliedStatus]));
            
            //return $data->Paginate(JobLists::LIMIT);
            return $res;
        }
    }
}
