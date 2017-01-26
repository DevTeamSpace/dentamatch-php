<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class SavedJobs extends Model
{
    use SoftDeletes;
  
    protected $table  = 'saved_jobs';
    protected $primaryKey = 'id';
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'created_at'
    ];
    
    const LIMIT = 10;
    
    public static function listSavedJobs($reqData){
        $latitude = $reqData['lat'];
        $longitude = $reqData['lng'];
        $searchQueryObj = SavedJobs::join('recruiter_jobs','saved_jobs.recruiter_job_id', '=', 'recruiter_jobs.id')
                        ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->where('saved_jobs.seeker_id','=' ,$reqData['userId']);
        
                //$total = $searchQueryObj->count();
                $total = 0;
                if($searchQueryObj){
                    //$array = $searchQueryObj->toArray();
                    $total = count($searchQueryObj);
                }
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
    
    
    

}
