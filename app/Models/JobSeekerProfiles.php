<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class JobSeekerProfiles extends Model
{
    
    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    
    const LIMIT = 10;

    public static function getJobSeekerProfiles($job,$reqData){
        $obj = JobSeekerProfiles::where('jobseeker_profiles.job_titile_id',$job->job_title_id);

        $obj->leftJoin('job_titles','jobseeker_profiles.job_titile_id','=','job_titles.id');

        if($job->job_type==RecruiterJobs::FULLTIME){
            $obj->where('jobseeker_profiles.is_fulltime',1);
        }
        elseif($job->job_type==RecruiterJobs::PARTTIME){
            $obj->where('jobseeker_profiles.is_fulltime',0);
            if($job->is_monday)
                $obj->where('jobseeker_profiles.is_parttime_monday',1);

            if($job->is_tuesday)
                $obj->where('jobseeker_profiles.is_parttime_tuesday',1);

            if($job->is_wednesday)
                $obj->where('jobseeker_profiles.is_parttime_wednesday',1);

            if($job->is_thursday)
                $obj->where('jobseeker_profiles.is_parttime_thursday',1);

            if($job->is_friday)
                $obj->where('jobseeker_profiles.is_parttime_friday',1);

            if($job->is_saturday)
                $obj->where('jobseeker_profiles.is_parttime_saturday',1);

            if($job->is_sunday)
                $obj->where('jobseeker_profiles.is_parttime_sunday',1);
        }
        elseif($job->job_type==RecruiterJobs::TEMPORARY){
            $obj->where('jobseeker_profiles.is_fulltime',0);
            
            $obj->leftJoin('jobseeker_temp_availability',function($query) use ($job){
                $query->on('jobseeker_temp_availability.user_id', '=', 'jobseeker_profiles.user_id')
                ->whereIn('jobseeker_temp_availability.temp_job_date',explode(',',$job->temp_job_dates));
            })->groupby('jobseeker_profiles.user_id');
            $obj->addSelect(DB::raw("group_concat(distinct(jobseeker_temp_availability.temp_job_date)) AS temp_job_dates"));
        }
        
        $obj->select('jobseeker_profiles.first_name','jobseeker_profiles.last_name','jobseeker_profiles.profile_pic',
                    'jobseeker_profiles.is_parttime_monday','jobseeker_profiles.is_parttime_tuesday','jobseeker_profiles.is_parttime_tuesday',
                    'jobseeker_profiles.is_parttime_wednesday','jobseeker_profiles.is_parttime_thursday','jobseeker_profiles.is_parttime_friday','jobseeker_profiles.is_parttime_saturday','jobseeker_profiles.is_parttime_sunday','jobseeker_profiles.is_fulltime','jobseeker_profiles.user_id','jobseeker_profiles.id','job_titles.jobtitle_name','jobseeker_profiles.latitude','jobseeker_profiles.longitude');

        $obj->leftJoin('jobseeker_skills as skill_count',function($query) use ($job){
                $query->on('jobseeker_profiles.user_id', '=', 'skill_count.user_id')
                ->whereIn('skill_count.skill_id',explode(',',$job->required_skills));
            })->groupby('skill_count.skill_id');
        $obj->addSelect(DB::raw("count(distinct(skill_count.skill_id)) AS matched_skills")); 

        $obj->addSelect(DB::raw("(
                        3959 * acos (
                          cos ( radians(".$job->latitude.") )
                          * cos( radians( jobseeker_profiles.latitude) )
                          * cos( radians(".$job->longitude.") - radians(jobseeker_profiles.longitude) )
                          + sin ( radians(".$job->latitude.") )
                          * sin( radians( jobseeker_profiles.latitude ) )
                         )) AS distance") )
            ->where(DB::raw("(
                        3959 * acos (
                          cos ( radians(".$job->latitude.") )
                          * cos( radians( jobseeker_profiles.latitude) )
                          * cos( radians(".$job->longitude.") - radians(jobseeker_profiles.longitude) )
                          + sin ( radians(".$job->latitude.") )
                          * sin( radians( jobseeker_profiles.latitude ) )
                         ))"),'<=',$reqData['distance']);

        $obj->leftjoin('job_ratings',function($query){
            $query->on('job_ratings.seeker_id', '=', 'jobseeker_profiles.user_id');
        })
        ->leftjoin('favourites',function($query){
            $query->on('favourites.seeker_id','=','jobseeker_profiles.user_id')
                ->where('favourites.recruiter_id',Auth::user()->id);
        })
        ->addSelect('favourites.seeker_id as is_favourite')
        ->addSelect(DB::raw("avg(punctuality) as punctuality"),DB::raw("avg(time_management) as time_management"),
                DB::raw("avg(skills) as skills"),DB::raw("avg(teamwork) as teamwork"),DB::raw("avg(onemore) as onemore"))
        ->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills)+avg(teamwork)+avg(onemore))/5 AS avg_rating"))
        ->groupby('jobseeker_profiles.user_id');

        $total  = 0;

        $obj->orderby('matched_skills','desc');
        $obj->orderby('distance');
        
        return $obj->paginate(RecruiterJobs::LIMIT);     
    } 

    public static function getJobSeekerDetails($seekerId, $job){
        $obj = JobSeekerProfiles::where('jobseeker_profiles.id',$seekerId);

        $obj->leftJoin('jobseeker_affiliations','jobseeker_profiles.user_id','=','jobseeker_affiliations.user_id')
            ->leftJoin('affiliations','jobseeker_affiliations.affiliation_id','=','affiliations.id');

        $obj->leftJoin('jobseeker_certificates','jobseeker_profiles.user_id','=','jobseeker_certificates.user_id')
            ->leftJoin('certifications','jobseeker_certificates.certificate_id','=','certifications.id');

        $obj->leftJoin('jobseeker_work_experiences','jobseeker_profiles.user_id','=','jobseeker_work_experiences.user_id');

        $obj->leftJoin('job_titles','jobseeker_profiles.job_titile_id','=','job_titles.id');

        $obj->leftJoin('jobseeker_temp_availability','jobseeker_profiles.user_id','=','jobseeker_temp_availability.user_id')
            ->groupby('jobseeker_profiles.user_id');
        
        $obj->select('jobseeker_profiles.first_name','jobseeker_profiles.last_name','jobseeker_profiles.profile_pic',
                    'jobseeker_profiles.is_parttime_monday','jobseeker_profiles.is_parttime_tuesday','jobseeker_profiles.is_parttime_tuesday',
                    'jobseeker_profiles.is_parttime_wednesday','jobseeker_profiles.is_parttime_thursday','jobseeker_profiles.is_parttime_friday','jobseeker_profiles.is_parttime_saturday','jobseeker_profiles.is_parttime_sunday','jobseeker_profiles.is_fulltime','jobseeker_profiles.user_id','jobseeker_profiles.id','job_titles.jobtitle_name','jobseeker_profiles.about_me', 'jobseeker_profiles.preferred_job_location')
            ->groupby('jobseeker_profiles.user_id', 'jobseeker_affiliations.user_id','jobseeker_certificates.user_id','jobseeker_work_experiences.user_id');

        $obj->addSelect(DB::raw("group_concat(distinct(affiliations.affiliation_name) SEPARATOR ', ') AS affiliations"));

        $obj->addSelect(DB::raw("group_concat(distinct(certifications.certificate_name) SEPARATOR ', ') AS certifications"));

        $obj->addSelect(DB::raw("group_concat(distinct(jobseeker_temp_availability.temp_job_date) SEPARATOR ' | ') AS temp_job_dates"));

        $obj->addSelect(DB::raw("(
                        3959 * acos (
                          cos ( radians(".$job->latitude.") )
                          * cos( radians( jobseeker_profiles.latitude) )
                          * cos( radians(".$job->longitude.") - radians(jobseeker_profiles.longitude) )
                          + sin ( radians(".$job->latitude.") )
                          * sin( radians( jobseeker_profiles.latitude ) )
                         )) AS distance") )
            ->having(DB::raw("(
                        3959 * acos (
                          cos ( radians(".$job->latitude.") )
                          * cos( radians( jobseeker_profiles.latitude) )
                          * cos( radians(".$job->longitude.") - radians(jobseeker_profiles.longitude) )
                          + sin ( radians(".$job->latitude.") )
                          * sin( radians( jobseeker_profiles.latitude ) )
                         )) AS distance"),'<=',$reqData['distance']);

        $searchResult   =   $obj->first();
        

        //dd($searchResult->toArray());

        $result = array();
        if($searchResult){
            $seekerUserId = $searchResult->user_id;                        
            
            $schoolings = JobSeekerSchooling::where('jobseeker_schoolings.user_id',$seekerUserId)
                            ->leftJoin('schoolings','jobseeker_schoolings.schooling_id','=','schoolings.id')
                            ->leftJoin('schoolings as school_title','schoolings.parent_id','=','school_title.id')
                            ->select('jobseeker_schoolings.other_schooling','jobseeker_schoolings.year_of_graduation','schoolings.school_name','school_title.school_name as school_title')
                            ->groupby('schoolings.parent_id')
                            ->addSelect(DB::raw("group_concat(schoolings.school_name SEPARATOR ', ') AS school_name"))
                            ->get();

            $skills     = JobSeekerSkills::where('jobseeker_skills.user_id',$seekerUserId)
                            ->leftJoin('skills','jobseeker_skills.skill_id','=','skills.id')
                            ->leftJoin('skills as skill_title','skills.parent_id','=','skill_title.id')
                            ->select('jobseeker_skills.other_skill','skills.skill_name','skill_title.skill_name as skill_title')
                            ->groupby('skills.parent_id')
                            ->addSelect(DB::raw("group_concat(skills.skill_name SEPARATOR ', ') AS skill_name"))
                            ->get(); 

            $experience = JobSeekerWorkExperiences::where('jobseeker_work_experiences.user_id',$seekerUserId)
                            ->leftJoin('job_titles','jobseeker_work_experiences.job_title_id','=','job_titles.id')
                            ->select('months_of_expereince','office_name','office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email', 'job_titles.jobtitle_name')
                            ->get();                                
            //dd($experience->toArray());
            
            $result                 =   $searchResult->toArray();
            $result['schoolings']   =   $schoolings->toArray();
            $result['skills']       =   $skills->toArray();
            $result['experience']   =   $experience->toArray();
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
}