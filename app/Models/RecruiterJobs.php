<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
class RecruiterJobs extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    const FULLTIME = 1;
    const PARTTIME = 2;
    const TEMPORARY = 3;
    
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
                                DB::raw("(
                    3959 * acos (
                      cos ( radians($latitude) )
                      * cos( radians( recruiter_offices.latitude) )
                      * cos( radians( $longitude ) - radians(recruiter_offices.longitude) )
                      + sin ( radians($latitude) )
                      * sin( radians( recruiter_offices.latitude ) )
                     )) AS distance"));
                $page = $reqData['page'];
                $limit = 10;
                $skip = 0;
                if($page>1){
                    $skip = ($page-1)*$limit;
                }
                $searchResult = $searchQueryObj->skip($skip)->take($limit)->get();
                $result = array();
                if($searchResult){
                    $result['list'] = $searchResult->toArray();
                    $result['total'] = $total;
                }
                return $result;
    }
    
}
    