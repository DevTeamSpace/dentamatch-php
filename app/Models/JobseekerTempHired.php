<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Auth;
use DB;
//use Illuminate\Database\Eloquent\SoftDeletes;

class JobSeekerTempHired extends Model {

    use Eloquence,
        Mappable;
//    use SoftDeletes;

    protected $table = 'jobseeker_temp_hired';
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];

    public static function getTempJobSeekerList($job, $forJobType='') {
        $obj = JobSeekerTempHired::join('recruiter_jobs', 'jobseeker_temp_hired.job_id', '=', 'recruiter_jobs.id')
                ->leftjoin('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->leftjoin('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'jobseeker_temp_hired.jobseeker_id')
                ->leftjoin('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
                ->leftjoin('job_lists', 'jobseeker_temp_hired.job_id', '=', 'job_lists.recruiter_job_id')
                ->where('jobseeker_temp_hired.job_id', $job['id'])
                ->whereIn('job_lists.applied_status', [JobLists::HIRED]);
        
        $obj->select('jobseeker_temp_hired.id','job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 'job_lists.seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type');

        $obj->leftjoin('job_ratings', function($query) {
                    $query->on('job_ratings.seeker_id', '=', 'jobseeker_temp_hired.jobseeker_id');
            })
            ->leftjoin('favourites',function($query){
                $query->on('favourites.seeker_id','=','jobseeker_temp_hired.jobseeker_id')
                    ->where('favourites.recruiter_id',Auth::user()->id);
            });

        if(isset($job['job_date']) && !empty($job['job_date'])){
            $obj->where('jobseeker_temp_hired.job_date', date('Y-m-d',strtotime($job['job_date'])) );
        }

        $obj->addSelect('favourites.seeker_id as is_favourite')
                ->leftJoin('temp_job_dates','job_lists.temp_job_id','=','temp_job_dates.id')
                ->addSelect(DB::raw("group_concat(temp_job_dates.job_date) AS temp_job_dates"))
                ->addSelect(DB::raw("avg(punctuality) as punctuality"),DB::raw("avg(time_management) as time_management"),
                        DB::raw("avg(skills) as skills"),DB::raw("avg(teamwork) as teamwork"),DB::raw("avg(onemore) as onemore"))
                ->addSelect(DB::raw("(avg(punctuality)+avg(time_management)+avg(skills)+avg(teamwork)+avg(onemore))/5 AS avg_rating"))
                ->groupby('jobseeker_temp_hired.jobseeker_id');
       
        $data = $obj->addSelect(DB::raw("(
                    3959 * acos (
                      cos ( radians(recruiter_offices.latitude) )
                      * cos( radians( jobseeker_profiles.latitude) )
                      * cos( radians( recruiter_offices.longitude    ) - radians(jobseeker_profiles.longitude) )
                      + sin ( radians(recruiter_offices.latitude) )
                      * sin( radians( jobseeker_profiles.latitude ) )
                     )) AS distance"))
                ->orderby('job_lists.applied_status', 'desc')
                ->orderby('distance', 'asc')
                ->get();

        return ($data->groupBy('applied_status')->toArray());
    }

}
