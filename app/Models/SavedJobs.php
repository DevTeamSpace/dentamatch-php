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
        $jobseekerSkills = JobSeekerSkills::select('jobseeker_skills.skill_id')
                            ->where("user_id", $reqData['userId'])
                            ->orderBy('jobseeker_skills.skill_id')
                            ->get()
                            ->map(function($jobseekerSkills) {
                                return $jobseekerSkills['skill_id'];
                            })->toArray();
        
        $searchQueryObj = SavedJobs::join('recruiter_jobs','saved_jobs.recruiter_job_id', '=', 'recruiter_jobs.id')
                        ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                        ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                        ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                        ->where('saved_jobs.seeker_id','=' ,$reqData['userId']);
        
        $searchQueryObj->join('template_skills',function($query) use ($jobseekerSkills){
                $query->on('template_skills.job_template_id','=','recruiter_jobs.job_template_id')
                        ->whereIn('template_skills.skill_id',$jobseekerSkills);
                });
        
        $searchQueryObj->join('template_skills as tmp_skills',function($query) {
                $query->on('tmp_skills.job_template_id','=','recruiter_jobs.job_template_id');
            });
        
        $searchQueryObj->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.is_monday',
                        'recruiter_jobs.is_tuesday','recruiter_jobs.is_wednesday',
                        'recruiter_jobs.is_thursday','recruiter_jobs.is_friday',
                        'recruiter_jobs.is_saturday','recruiter_jobs.is_sunday',
                        'job_titles.jobtitle_name','recruiter_profiles.office_name',
                        'recruiter_offices.address','recruiter_offices.zipcode',
                        'recruiter_offices.latitude','recruiter_offices.longitude','recruiter_jobs.created_at',
                        DB::raw("DATEDIFF(now(), recruiter_jobs.created_at) AS days"));

        $searchQueryObj->addSelect(DB::raw("count(distinct(template_skills.skill_id)) AS matched_skills")); 
        $searchQueryObj->addSelect(DB::raw("count(distinct(tmp_skills.skill_id)) AS template_skills_count")); 
        $searchQueryObj->addSelect(DB::raw("IF(count(distinct(template_skills.skill_id))>0, (count(distinct(template_skills.skill_id))/count(distinct(tmp_skills.skill_id)))*100,0) AS percentaSkillsMatch")); 
        
        $total = $searchQueryObj->distinct('saved_jobs.recruiter_job_id')->count('saved_jobs.recruiter_job_id');
        
        $page = $reqData['page'];
        $limit = SavedJobs::LIMIT ;
        $skip = 0;
        if($page > 1){
            $skip = ($page-1)* $limit;
        }
        $searchResult = $searchQueryObj->groupby('saved_jobs.recruiter_job_id')->skip($skip)->take($limit)->get();
        $result = array();
        if($searchResult){
            $result['list'] = $searchResult->toArray();
            $result['total'] = $total;
        }
        return $result;
    }
    
    public static function getJobSavedStatus($jobId, $userId)
    {
        $return = 0;
        
        $savedJobs = static::where('seeker_id', $userId)->where('recruiter_job_id', $jobId)->first();
        
        if($savedJobs) {
            $return = 1;
        }
        return $return;
        
    }
    

}
