<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Models\UserProfile;
use App\Models\SavedJobs;

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
        print_r($userSavedJobs);exit();
        $latitude = $reqData['lat'];
        $longitude = $reqData['lng'];
                $searchQueryObj = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->leftjoin('saved_jobs','saved_jobs.recruiter_job_id', '=', 'recruiter_jobs.id')
                        ->whereIn('job_titles.id', $reqData['jobTitle']);
                if($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 0){
                    $searchQueryObj->where('recruiter_jobs.job_type',1);
                }
                if($reqData['isFulltime'] == 0 && $reqData['isParttime'] == 1){
                    $searchQueryObj->where('recruiter_jobs.job_type',2);
                    if(is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0){
                        foreach($reqData['parttimeDays'] as $day){
                            $searchQueryObj->orWhere('is_'.$day, 1);
                        }
                    }
                }
                if($reqData['isFulltime'] == 1 && $reqData['isParttime'] == 1){
                    $searchQueryObj->whereIn('recruiter_jobs.job_type',[1,2]);
                    if(is_array($reqData['parttimeDays']) && count($reqData['parttimeDays']) > 0){
                        foreach($reqData['parttimeDays'] as $day){
                            $searchQueryObj->orWhere('is_'.$day, 1);
                        }
                    }
                }
                $total = $searchQueryObj->count();
                $searchQueryObj->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
                                'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday',
                                'recruiter_jobs.is_thursday','recruiter_jobs.is_friday',
                                'recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
                                'job_titles.jobtitle_name','recruiter_profiles.office_name',
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
                $searchResult = $searchQueryObj->skip($skip)->take($limit)->get();
                $result = array();
                $updatedResult = array();
                if($searchResult){
                    $resultArray = $searchResult->toArray();
                    foreach($resultArray as $value){
                        $isSaved = 0;
                        if(in_array($value['id'],$savedJobsArray)){
                            $isSaved = 1;
                        }
                        $value['isSaved'] = 1;
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
        
        $searchQueryObj->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
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
        $tempDates =  $data->tempJobActiveDates;
        $tempJob = [];
        if($tempDates) {
            foreach($tempDates as $value) {
                $tempJob[]= $value->job_date;
            }
            
        }
        $searchResult = $searchQueryObj->first()->toArray();
        $searchResult['job_type_dates'] = [];
        if($searchResult)
        {
            $searchResult['job_type_string'] = static::$jobTypeName[$searchResult['job_type']];
            $searchResult['job_type_dates'] = $tempJob;
        }
        
        return $searchResult;
    }
}
    