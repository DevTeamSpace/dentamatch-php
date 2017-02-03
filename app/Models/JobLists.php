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
    
    
    
    public static function getJobSeekerList($job){
        $obj = JobLists::join('recruiter_jobs','job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('jobseeker_profiles','jobseeker_profiles.user_id','=','job_lists.seeker_id')
                ->join('job_titles','jobseeker_profiles.job_titile_id','=','job_titles.id')
                ->where('job_lists.recruiter_job_id',$job->id)
                ->whereIn('job_lists.applied_status',[ JobLists::INVITED, JobLists::APPLIED,JobLists::SHORTLISTED,JobLists::HIRED])
                ->select('job_lists.applied_status','jobseeker_profiles.first_name','jobseeker_profiles.last_name',
            'jobseeker_profiles.profile_pic','job_lists.seeker_id','job_titles.jobtitle_name','recruiter_jobs.job_type');
        
        if($job->job_type==RecruiterJobs::FULLTIME){
            $obj->addSelect('jobseeker_profiles.is_fulltime');
        }
        elseif($job->job_type==RecruiterJobs::PARTTIME){
            $obj->addSelect('jobseeker_profiles.is_parttime_monday','jobseeker_profiles.is_parttime_tuesday',
                    'jobseeker_profiles.is_parttime_wednesday','jobseeker_profiles.is_parttime_thursday',
                    'jobseeker_profiles.is_parttime_friday','jobseeker_profiles.is_parttime_saturday',
                    'jobseeker_profiles.is_parttime_sunday');
        }
        elseif($job->job_type==RecruiterJobs::TEMPORARY){
            $obj->leftjoin('jobseeker_temp_availability',function($query) use ($job){
                $query->on('jobseeker_temp_availability.user_id', '=', 'job_lists.seeker_id')
                ->whereIn('jobseeker_temp_availability.temp_job_date',explode(',',$job->temp_job_dates));
            })
            ->groupby('job_lists.applied_status','job_lists.seeker_id');
            $obj->addSelect(DB::raw("group_concat(jobseeker_temp_availability.temp_job_date) AS temp_job_dates"));
        }
        
        $data = $obj->addSelect(DB::raw("(
                    3959 * acos (
                      cos ( radians(recruiter_offices.latitude) )
                      * cos( radians( jobseeker_profiles.latitude) )
                      * cos( radians( recruiter_offices.longitude    ) - radians(jobseeker_profiles.longitude) )
                      + sin ( radians(recruiter_offices.latitude) )
                      * sin( radians( jobseeker_profiles.latitude ) )
                     )) AS distance"))
                ->orderby('job_lists.applied_status','desc')
                ->orderby('distance','asc')
                    ->get();
      dd($data->groupBy('applied_status')->toArray());  
    }

    public static function getJobSeekerListByFilter($jobType, $jobTitle){
        $obj = JobLists::join('recruiter_jobs','job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('jobseeker_profiles','jobseeker_profiles.user_id','=','job_lists.seeker_id')
                ->join('job_titles','jobseeker_profiles.job_titile_id','=','job_titles.id')
                ->whereIn('job_lists.applied_status',[ JobLists::INVITED, JobLists::APPLIED,JobLists::SHORTLISTED,JobLists::HIRED])
                ->where('job_titles.id',$jobTitle)
                ->select('job_lists.applied_status','jobseeker_profiles.first_name','jobseeker_profiles.last_name',
            'jobseeker_profiles.profile_pic','job_lists.seeker_id','job_titles.jobtitle_name','recruiter_jobs.job_type');
        
        if($jobType==RecruiterJobs::FULLTIME){
            $obj->addSelect('jobseeker_profiles.is_fulltime');
        }
        elseif($jobType==RecruiterJobs::PARTTIME){
            $obj->addSelect('jobseeker_profiles.is_parttime_monday','jobseeker_profiles.is_parttime_tuesday',
                    'jobseeker_profiles.is_parttime_wednesday','jobseeker_profiles.is_parttime_thursday',
                    'jobseeker_profiles.is_parttime_friday','jobseeker_profiles.is_parttime_saturday',
                    'jobseeker_profiles.is_parttime_sunday');
        }
        elseif($jobType==RecruiterJobs::TEMPORARY){
            $obj->leftjoin('jobseeker_temp_availability',function($query) use ($job){
                $query->on('jobseeker_temp_availability.user_id', '=', 'job_lists.seeker_id');
            })
            ->groupby('job_lists.applied_status','job_lists.seeker_id');
            $obj->addSelect(DB::raw("group_concat(jobseeker_temp_availability.temp_job_date) AS temp_job_dates"));
        }
        
        $data = $obj->addSelect(DB::raw("(
                    3959 * acos (
                      cos ( radians(recruiter_offices.latitude) )
                      * cos( radians( jobseeker_profiles.latitude) )
                      * cos( radians( recruiter_offices.longitude    ) - radians(jobseeker_profiles.longitude) )
                      + sin ( radians(recruiter_offices.latitude) )
                      * sin( radians( jobseeker_profiles.latitude ) )
                     )) AS distance"))
                ->orderby('job_lists.applied_status','desc')
                ->orderby('distance','asc')
                ->get();

        return $data->toArray();
    }
}