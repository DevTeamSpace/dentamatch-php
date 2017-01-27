<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobLists extends Model
{
    
    const INVITED = 1;
    const APPLIED = 2;
    const SHORTLISTED = 3;
    const HIRED = 4;
    const REJECTED = 5;
    const CANCELLED = 6;
    
    protected $table = 'job_lists';
    protected $primaryKey = 'id';
    
    const LIMIT = 10;
    
    public static function listJobsByStatus($reqData){
        $latitude = $reqData['lat'];
        $longitude = $reqData['lng'];
        $searchQueryObj = JobLists::join('recruiter_jobs','job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                        ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->where('job_lists.seeker_id','=' ,$reqData['userId']);
        if($reqData['type'] == 2){
                       $searchQueryObj->where('job_lists.applied_status', '=' , JobLists::APPLIED);       
        }else{
                        $searchQueryObj->where('job_lists.applied_status', '=' , JobLists::CANCELLED); 
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
                $limit = SavedJobs::LIMIT ;
                $skip = 0;
                if($page > 1){
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
    
    public static function isJobApplied($jobId,$userId)
    {
        $return = 0;
        $jobExists = static::where('seeker_id','=',$userId)
                                ->where('recruiter_job_id','=',$jobId)
                                ->where('applied_status','=',JobLists::APPLIED)
                                ->first();
        if($jobExists) {
            $return = 1;
        }
        return $return;
    }
    
}