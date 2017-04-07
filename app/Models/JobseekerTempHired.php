<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class JobSeekerTempHired extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_temp_hired';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at'
    ];
    
    
    public static function hiredTempJobPendingRating() {
         $jobs = static::select(['recruiter_jobs.id as recruitedJobId', 'jobseeker_temp_hired.jobseeker_id as jobSeekerId', 'job_ratings.seeker_id as ratedJobSeekerId'])
                ->where(['applied_status' => JobLists::HIRED,'recruiter_jobs.job_type' => RecruiterJobs::TEMPORARY, 'job_templates.user_id' => Auth::user()->id])
                ->join('recruiter_jobs', 'jobseeker_temp_hired.job_id', '=', 'recruiter_jobs.id')
                ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->leftjoin('job_ratings',function($query){
                    $query->on('job_ratings.recruiter_job_id', '=', 'recruiter_jobs.id')
                        ->on('job_ratings.seeker_id', '=', 'jobseeker_temp_hired.jobseeker_id');
                })
                ->where('jobseeker_temp_hired.job_date','<=',date("Y-m-d", strtotime("-1 days")))
                ->distinct();
        $jobSeekerCount = $jobs->get()->count();
        $ratedSeekerCount = $jobs->whereNotNull('job_ratings.seeker_id')->get()->count();
        return ['seekerCount' => $jobSeekerCount, 'ratedSeekerCount' => $ratedSeekerCount];
    }
    
}
