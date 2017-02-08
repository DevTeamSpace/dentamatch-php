<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

use App\Models\UserProfile;
use App\Models\SavedJobs;
use Auth;

class RecruiterJobs extends Model
{
    use SoftDeletes;
    const FULLTIME = 1;
    const PARTTIME = 2;
    const TEMPORARY = 3;
    const LIMIT = 10;
    
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
        $latitude = $reqData['lat'];
        $longitude = $reqData['lng'];
                /*$searchQueryObj = RecruiterJobs::leftJoin('job_lists','job_lists.recruiter_job_id','=','recruiter_jobs.id')
                        ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->whereNull('job_lists.id')
                        ->whereIn('job_titles.id', $reqData['jobTitle']);*/
                $searchQueryObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->whereIn('job_templates.job_title_id', $reqData['jobTitle']);
                if($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 0){
                    $searchQueryObj->where('recruiter_jobs.job_type',1);
                }
                if($reqData['isFulltime'] == 0 && $reqData['isParttime'] == 1){
                    $searchQueryObj->where('recruiter_jobs.job_type',2);
                    if(is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0){
                        //$daysArray = ['is_monday'=>0, 'is_tuesday'=>0, 'is_wednesday'=>0, 'is_thursday'=>0, 'is_friday'=>0, 'is_saturday'=>0, 'is_sunday'=>0];
                        foreach($reqData['parttimeDays'] as $key => $day){
                            //foreach($reqData['parttimeDays'] as $key => $day){
                            //$searchQueryObj->orWhere('is_'.$day, 1);
                            if($key == 0){
                                $searchQueryObj->Where('is_'.$day, 1);
                            }else{
                                $searchQueryObj->orWhere('is_'.$day, 1);
                            }
                        //}
                        }
                         //$searchQueryObj->where($daysArray);
                    }
                }
                if($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 1){
                    $searchQueryObj->whereIn('recruiter_jobs.job_type',[1,2]);
                    if(is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0){
                        foreach($reqData['parttimeDays'] as $key => $day){
                            $searchQueryObj->orWhere('is_'.$day, 1);
                            /*if($key == 0){
                                $searchQueryObj->Where('is_'.$day, 1);
                            }else{
                                $searchQueryObj->orWhere('is_'.$day, 1);
                            }*/
                            //$searchQueryObj->orWhere('is_'.$day, 1);
                        }
                    }
                }
                $total = $searchQueryObj->count();
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
                $page = $reqData['page'];
                $limit = RecruiterJobs::LIMIT ;
                $skip = 0;
                if($page>1){
                    $skip = ($page-1)* $limit;
                }
                $searchResult = $searchQueryObj->skip($skip)->take($limit)->orderBy('distance', 'asc')->get();
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
                            'job_titles.jobtitle_name','recruiter_profiles.office_name',
                            'recruiter_offices.address','recruiter_offices.zipcode',
                            'recruiter_offices.latitude','recruiter_offices.longitude','recruiter_jobs.created_at',
                            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS job_posted_time_gap"),
                            DB::raw("GROUP_CONCAT(office_types.officetype_name) AS office_type_name"),
                            DB::raw("(3959 * acos (cos ( radians($latitude) )* cos( radians( recruiter_offices.latitude) ) * cos( radians( $longitude ) - radians(recruiter_offices.longitude) ) + sin ( radians($latitude) ) * sin( radians( recruiter_offices.latitude ) ) )) AS distance")
                        );
                        
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
        
        $jobObj->leftJoin('temp_job_dates','temp_job_dates.recruiter_job_id', '=' , 'recruiter_jobs.id')
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
            DB::raw("group_concat(job_lists.applied_status) AS applied_status"),
            DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));
        
        
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
            ->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday','recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday','recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs','recruiter_jobs.created_at','recruiter_jobs.job_template_id',
            'recruiter_profiles.office_name','recruiter_profiles.office_desc',
            'recruiter_offices.address','recruiter_offices.zipcode','recruiter_offices.latitude','recruiter_offices.longitude',
            'job_templates.template_name','job_templates.template_desc','job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(office_types.officetype_name) AS officetype_name"),
            DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"),
            DB::raw("group_concat(template_skills.skill_id) AS required_skills"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));
    
        return $jobObj->first()->toArray();
    } 
}
    