<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Auth;
class RecruiterJobs extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    const FULLTIME = 1;
    const PARTTIME = 2;
    const TEMPORARY = 3;
    const LIMIT = 2;
    
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
    public static function searchJob($reqData){
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
                                DB::raw("IF(saved_jobs.recruiter_job_id IS NULL,0,1) AS is_saved"),
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
                if($searchResult){
                    $result['list'] = $searchResult->toArray();
                    $result['total'] = $total;
                }
                return $result;
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
    
    public static function getJobDetails($jobId){
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
            ->groupBy('recruiter_jobs.id','recruiter_profiles.office_name','recruiter_profiles.office_desc','office_types.officetype_name')
            ->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
            'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday','recruiter_jobs.is_thursday',
            'recruiter_jobs.is_friday','recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
            'recruiter_jobs.no_of_jobs','recruiter_jobs.created_at','recruiter_jobs.job_template_id',
            'recruiter_profiles.office_name','recruiter_profiles.office_desc',
            'recruiter_offices.address','recruiter_offices.zipcode',
            'job_templates.template_name','job_templates.template_desc','job_templates.job_title_id',
            'job_titles.jobtitle_name',
            DB::raw("group_concat(office_types.officetype_name) AS officetype_name"),
            DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"),
            DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));
        $jobData = $jobObj->first()->toArray();
        return $jobObj->first();
        
    }
}
    