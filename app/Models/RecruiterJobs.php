<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

use App\Models\UserProfile;
use App\Models\SavedJobs;
use Auth;
use App\Models\Configs;

class RecruiterJobs extends Model
{
    use SoftDeletes;
    const FULLTIME = 1;
    const PARTTIME = 2;
    const TEMPORARY = 3;
    const LIMIT = 10;
    const INVITED = 1;
    const APPLIED = 2;
    const SHORTLISTED = 3;
    const HIRED = 4;
    const REJECTED = 5;
    const CANCELLED = 6;
    
    static $jobTypeName = ['1'=>'Full Time', '2'=>'Part Time', '3'=>'Temp'];
    
    protected $table = 'recruiter_jobs';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'jobTemplateId' => 'job_template_id',
        'recuriterOfficeId'=>'recruiter_office_id',
        'jobType' => 'job_type',
        'noOfJobs' => 'no_of_jobs',
        'isMonday' => 'is_monday',
        'isTuesday'=>'is_tuesday',
        'isWednesday' => 'is_wednesday',
        'isThursday' => 'is_thursday',
        'isFriday' => 'is_friday',
        'isSaturday'=>'is_saturday',
        'isSunday' => 'is_sunday',
        'isPublished' => 'is_published',
        ];
    protected $hidden       = ['updated_at','deleted_at'];
    protected $fillable     = ['jobTemplateId','recuriterOfficeId','jobType','noOfJobs','isMonday',
        'isTuesday','isWednesday','isThursday','isFriday','isSaturday','isSunday','isPublished'];
    //protected $appends      = ['userId','templateName','templateDesc'];

    protected $dates = ['deleted_at'];
    
    public function tempJobDates(){
        return $this->hasMany(TempJobDates::class,'recruiter_job_id');
    }
    
    public function tempJobActiveDates() {
        return $this->hasMany(TempJobDates::class,'recruiter_job_id')->select('job_date');
    }
    
    public static function searchJob($reqData){
        $savedJobsResult  = SavedJobs::select('recruiter_job_id')->where('seeker_id',$reqData['userId'])->get();
        $userSavedJobs = array();
        if($savedJobsResult){
                    $savedJobsArray = $savedJobsResult->toArray();
                    $userSavedJobs = array_map(function ($value) {
                        return  $value['recruiter_job_id'];
                    }, $savedJobsArray);
                }
        //$latitude = $reqData['lat'];
        //$longitude = $reqData['lng'];
        
        $userProfile = UserProfile::where('user_id', $reqData['userId'])->first();
        $longitude = $userProfile->longitude;
        $latitude = $userProfile->latitude;
        $searchQueryObj = RecruiterJobs::leftJoin('job_lists',function($query) use ($reqData){
            $query->on('job_lists.recruiter_job_id','=','recruiter_jobs.id')
                  ->where('job_lists.seeker_id','=', $reqData['userId']);
        })
                ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
               // ->whereNull('job_lists.id')
                //->where('job_lists.seeker_id','!=', $reqData['userId'])
                ->whereIn('job_templates.job_title_id', $reqData['jobTitle']);

                //->whereIn('job_titles.id', $reqData['jobTitle']);
        /*$searchQueryObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                ->whereIn('job_templates.job_title_id', $reqData['jobTitle']);*/

        $blockedRecruiterModel = ChatUserLists::getBlockedRecruiters($reqData['userId']);
        if(!empty($blockedRecruiterModel)) {
            $blockedRecruiter = array_map(function ($value) {
                        return  $value['recruiter_id'];
                    }, $blockedRecruiterModel);
            $searchQueryObj->whereNotIn('recruiter_profiles.user_id',$blockedRecruiter);
        }
        if($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 0){
            $searchQueryObj->where('recruiter_jobs.job_type',1);
        }

        if($reqData['isFulltime'] == 0 && $reqData['isParttime'] == 1){
            $searchQueryObj->where('recruiter_jobs.job_type',2);
            $searchQueryObj->where(function($query) use ($reqData){
                if(is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0){
                    //$daysArray = ['is_monday'=>0, 'is_tuesday'=>0, 'is_wednesday'=>0, 'is_thursday'=>0, 'is_friday'=>0, 'is_saturday'=>0, 'is_sunday'=>0];
                    foreach($reqData['parttimeDays'] as $day){
                        $query->orWhere('is_'.$day, 1);
                    }
                }
            });
        }


        $searchQueryObj->where(function($query) use ($reqData){
            if($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 1){
                $query->where('recruiter_jobs.job_type',1);
                if(is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0){
                    $query->orWhere(function($query1) use ($reqData){
                        $query1->where('recruiter_jobs.job_type',2);
                        $query1->where(function($query2) use ($reqData){
                            foreach($reqData['parttimeDays'] as  $day){
                                $query2->orWhere('is_'.$day, 1);
                            }
                        });
                    });
                }
            }
        });
        //$radius = Configs::select('config_data')->where('config_name','=','SEARCHRADIUS')->first();
        //$searchQueryObj->where('distance','<=',$radius->config_data);
        //$searchQueryObj->groupby('recruiter_jobs.id');
        
        
        $searchQueryObj->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
                        'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday',
                        'recruiter_jobs.is_thursday','recruiter_jobs.is_friday',
                        'recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
                        'job_titles.jobtitle_name','recruiter_profiles.office_name','job_templates.job_title_id',
                        'recruiter_offices.address','recruiter_offices.zipcode',
                        'recruiter_offices.latitude','recruiter_offices.longitude','recruiter_jobs.created_at',
                        DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"),
                        DB::raw("(
            3959 * acos (
              cos ( radians($latitude) )
              * cos( radians( recruiter_offices.latitude) )
              * cos( radians( $longitude ) - radians(recruiter_offices.longitude) )
              + sin ( radians($latitude) )
              * sin( radians( recruiter_offices.latitude ) )
             )) AS distance"));
        $total = $searchQueryObj->distinct('recruiter_jobs.id')->count();
        $page = $reqData['page'];
        $limit = RecruiterJobs::LIMIT ;
        $skip = 0;
        if($page>1){
            $skip = ($page-1)* $limit;
        }
        $searchResult = $searchQueryObj->distinct('recruiter_jobs.id')->skip($skip-1)->take($limit)->orderBy('distance', 'asc')->get();
        $result = array();
        $updatedResult = array();
        if($searchResult){
            $resultArray = $searchResult->toArray();
            foreach($resultArray as $value){
                $isSaved = 0;
                if((count($userSavedJobs) > 0) && (in_array($value['id'],$userSavedJobs))){
                    $isSaved = 1;
                }
                $value['isSaved'] = $isSaved;
                $updatedResult[] = $value;
            }
            $result['list'] = $updatedResult;
            //$result['list'] = $searchResult->toArray();

            $result['total'] = $total;
        }
        return $result;
    }
    
    public static function getJobDetail($jobId, $userId)
    {
        $tempJob = [];
        $searchResult = [];
        $userProfile = UserProfile::where('user_id', $userId)->first();
        $longitude = $userProfile->longitude;
        $latitude = $userProfile->latitude;
        $searchQueryObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->join('recruiter_office_types','recruiter_office_types.recruiter_office_id', '=' , 'recruiter_offices.id')
                        ->leftjoin('office_types','recruiter_office_types.office_type_id', '=' , 'office_types.id')
                        ->where('recruiter_jobs.id', $jobId)
                        ->groupBy('recruiter_jobs.id');
        
        $searchQueryObj->select('recruiter_jobs.id', 'recruiter_jobs.no_of_jobs','recruiter_jobs.job_type','recruiter_jobs.is_monday',
                            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday',
                            'recruiter_jobs.is_thursday','recruiter_jobs.is_friday',
                            'recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
                            'job_templates.template_name', 'job_templates.template_desc',
                            'recruiter_offices.work_everyday_start', 'recruiter_offices.work_everyday_end', 
                            'recruiter_offices.monday_start', 'recruiter_offices.monday_end', 
                            'recruiter_offices.tuesday_start', 'recruiter_offices.tuesday_end', 
                            'recruiter_offices.wednesday_start', 'recruiter_offices.wednesday_end', 
                            'recruiter_offices.thursday_start', 'recruiter_offices.thursday_end', 
                            'recruiter_offices.friday_start', 'recruiter_offices.friday_start', 
                            'recruiter_offices.saturday_start', 'recruiter_offices.saturday_end', 
                            'recruiter_offices.sunday_start', 'recruiter_offices.sunday_end', 
                            'job_titles.jobtitle_name','recruiter_profiles.office_name','recruiter_profiles.office_desc',
                            'recruiter_offices.address','recruiter_offices.zipcode',
                            'recruiter_offices.latitude','recruiter_offices.longitude','recruiter_jobs.created_at',
                            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS job_posted_time_gap"),
                            DB::raw("GROUP_CONCAT(office_types.officetype_name) AS office_type_name"),
                            DB::raw("(
                    3959 * acos (
                      cos ( radians($latitude) )
                      * cos( radians( recruiter_offices.latitude) )
                      * cos( radians( $longitude ) - radians(recruiter_offices.longitude) )
                      + sin ( radians($latitude) )
                      * sin( radians( recruiter_offices.latitude ) )
                     )) AS distance"));
                        
                        
        $data = $searchQueryObj->first();
        if(!empty($data)) {
            $tempDates = $data->tempJobActiveDates;
            if(!empty($tempDates)) {
                $searchResult['job_type_dates'] = [];
                foreach($tempDates as $value) {
                    $tempJob[]= $value->job_date;
                }
            }
            
            $searchResult = $searchQueryObj->first()->toArray();
        }
        
        if($searchResult)
        {
            $searchResult['job_type_string'] = static::$jobTypeName[$searchResult['job_type']];
            $searchResult['job_type_dates'] = $tempJob;
        }
        
        return $searchResult;
    }
    
    
    public static function getJobs(){
        $jobObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates',function($query){
                $query->on('job_templates.id','=','recruiter_jobs.job_template_id')
                ->where('job_templates.user_id',Auth::user()->id);
            })
            ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
            ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id');
        
        //$jobObj->leftJoin('temp_job_dates','temp_job_dates.recruiter_job_id', '=' , 'recruiter_jobs.id')
            $jobObj ->leftJoin('temp_job_dates',function($query){
                $query->on('temp_job_dates.recruiter_job_id','=','recruiter_jobs.id')
                ->whereDate('temp_job_dates.job_date','>=',date('Y-m-d').' 00:00:00');
            })
            ->leftJoin('job_lists',function($query){
                $query->on('job_lists.recruiter_job_id','=','recruiter_jobs.id')
                ->whereIn('job_lists.applied_status',[JobLists::INVITED,  JobLists::APPLIED]);
            })
            ->groupBy('recruiter_jobs.id','recruiter_profiles.office_name','recruiter_profiles.office_desc');
        $jobObj->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday','recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday','recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs','recruiter_jobs.created_at',
            'recruiter_profiles.office_name','recruiter_profiles.office_desc',
            'recruiter_offices.address','recruiter_offices.zipcode',
            'job_templates.template_name','job_templates.template_desc','job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(distinct concat(job_lists.seeker_id,'_', job_lists.applied_status)) AS applied_status"),
            DB::raw("group_concat(distinct(temp_job_dates.job_date) ORDER BY temp_job_dates.job_date ASC) AS temp_job_dates"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"))
            ->orderBy('recruiter_jobs.id','desc');
        
        return $jobObj->paginate(RecruiterJobs::LIMIT);
        
    }
    
    public static function getRecruiterJobDetails($jobId){
        $jobObj = RecruiterJobs::where('recruiter_jobs.id',$jobId)
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
            ->join('job_templates',function($query){
                $query->on('job_templates.id','=','recruiter_jobs.job_template_id')
                ->where('job_templates.user_id',Auth::user()->id);
            })
            ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
            ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
            ->leftJoin('temp_job_dates','temp_job_dates.recruiter_job_id', '=' , 'recruiter_jobs.id')
            ->leftJoin('template_skills','job_templates.id','=','template_skills.job_template_id')
            ->groupBy('recruiter_jobs.id','recruiter_profiles.office_name','recruiter_profiles.office_desc','office_types.officetype_name','template_skills.job_template_id')
            ->select('recruiter_jobs.id','recruiter_jobs.recruiter_office_id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday','recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday','recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs','recruiter_jobs.created_at','recruiter_jobs.job_template_id',
            'recruiter_profiles.office_name','recruiter_profiles.office_desc',
            'recruiter_offices.address','recruiter_offices.zipcode','recruiter_offices.latitude','recruiter_offices.longitude',
            'job_templates.template_name','job_templates.template_desc','job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(distinct(office_types.officetype_name)) AS officetype_name"),
            DB::raw("group_concat(distinct(temp_job_dates.job_date) ORDER BY temp_job_dates.job_date ASC) AS temp_job_dates"),
            DB::raw("group_concat(distinct(template_skills.skill_id)) AS required_skills"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));
            
        return $jobObj->first()->toArray();
    }
    
    public static function getAllTempJobs(){
        $jobObj = RecruiterJobs::where('recruiter_jobs.job_type',RecruiterJobs::TEMPORARY)
            ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('recruiter_office_types','recruiter_office_types.recruiter_office_id', '=' , 'recruiter_offices.id')
            ->leftjoin('office_types','recruiter_office_types.office_type_id', '=' , 'office_types.id')
            ->join('job_templates',function($query){
                $query->on('job_templates.id','=','recruiter_jobs.job_template_id')
                ->where('job_templates.user_id',Auth::user()->id);
            })
            ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
            ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id');
        
            $jobObj->leftJoin('temp_job_dates','temp_job_dates.recruiter_job_id', '=' , 'recruiter_jobs.id')
           
            ->leftJoin('job_lists',function($query){
                $query->on('job_lists.recruiter_job_id','=','recruiter_jobs.id')
                ->whereIn('job_lists.applied_status',[RecruiterJobs::HIRED]);
            })
            ->groupBy('recruiter_jobs.id','recruiter_profiles.office_name','recruiter_profiles.office_desc');
        $jobObj->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday','recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday','recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs','recruiter_jobs.created_at',
            'recruiter_profiles.office_name','recruiter_profiles.office_desc',
            'recruiter_offices.address','recruiter_offices.zipcode',
            'job_templates.template_name','job_templates.template_desc','job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(job_lists.applied_status) AS applied_status"),
            DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"),
            DB::raw("GROUP_CONCAT(office_types.officetype_name) AS office_type_name"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));
    
        return $jobObj->get();
    }
    
    public static function getTempJobsReports(){
        $jobs = RecruiterJobs::where(['recruiter_jobs.job_type' => RecruiterJobs::TEMPORARY, 'recruiter_offices.user_id' => Auth::user()->id])
                ->join('recruiter_offices', 'recruiter_offices.id', '=', 'recruiter_jobs.recruiter_office_id')
                ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                ->groupBy('job_titles.jobtitle_name');
        $jobs->select('job_titles.id as job_title_id', 'job_titles.jobtitle_name',
                DB::raw("COUNT(job_titles.id) as jobs_count"));
        return $jobs->get();
    }
    
    public static function getIndividualTempJob($job_title_id){
        $jobs = RecruiterJobs::where(['recruiter_jobs.job_type' => RecruiterJobs::TEMPORARY, 'recruiter_offices.user_id' => Auth::user()->id, 'job_titles.id' => $job_title_id])
                ->join('recruiter_offices', 'recruiter_offices.id', '=', 'recruiter_jobs.recruiter_office_id')
                ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                ->orderBy('recruiter_jobs.created_at', 'desc');
        $jobs->select('recruiter_jobs.created_at as job_created_at', 'recruiter_jobs.id as recruiter_job_id', 'recruiter_jobs.job_type');
        return $jobs->get();
    }
    
    public static function checkPendingTempJobsRating() {
        $jobs = RecruiterJobs::select(['recruiter_jobs.id as recruitedJobId', 'job_lists.seeker_id as jobSeekerId', 'job_ratings.seeker_id as ratedJobSeekerId'])
                ->where(['applied_status' => JobLists::HIRED,'recruiter_jobs.job_type' => RecruiterJobs::TEMPORARY, 'job_templates.user_id' => Auth::user()->id])
                ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->leftjoin('job_ratings', 'job_ratings.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->distinct();
        
        $jobSeekerCount = $jobs->get()->count();
        $ratedSeekerCount = $jobs->whereNotNull('job_ratings.seeker_id')->get()->count();
        return ['seekerCount' => $jobSeekerCount, 'ratedSeekerCount' => $ratedSeekerCount];
    }
}
    