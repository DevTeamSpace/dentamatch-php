<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use DB;

/**
 * App\Models\JobseekerTempHired
 *
 * @property int $id
 * @property int $jobseeker_id
 * @property int $job_id
 * @property string $job_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property-read string|null $mapping_for
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobseekerTempHired onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereJobDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereJobseekerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobseekerTempHired whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobseekerTempHired withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobseekerTempHired withoutTrashed()
 * @mixin \Eloquent
 */
class JobseekerTempHired extends Model {
    
    use Eloquence,SoftDeletes,
        Mappable;

    protected $table = 'jobseeker_temp_hired';
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];

    public static function getTempJobSeekerList($job, $forJobType='') {
        $obj = JobseekerTempHired::join('recruiter_jobs', 'jobseeker_temp_hired.job_id', '=', 'recruiter_jobs.id')
                ->leftjoin('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->leftjoin('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'jobseeker_temp_hired.jobseeker_id')
                ->leftjoin('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
                ->leftjoin('job_lists', 'jobseeker_temp_hired.job_id', '=', 'job_lists.recruiter_job_id')
                ->where('jobseeker_temp_hired.job_id', $job['id'])
                ->whereIn('job_lists.applied_status', [JobLists::HIRED]);
        
        $obj->select('jobseeker_temp_hired.id','jobseeker_temp_hired.job_date','job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 'jobseeker_temp_hired.jobseeker_id as seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type');

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
       
        $data = $obj->orderby('job_lists.applied_status', 'desc')
                ->get();

        return ($data->groupBy('applied_status')->toArray());
    }
    
    public static function getCurrentDayJobSeekerList() {
        $obj = JobLists::join('recruiter_jobs', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                ->leftjoin('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->leftjoin('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                ->leftjoin('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
                ->whereDate('job_lists.updated_at', '=', date('Y-m-d'))
                ->whereIn('job_lists.applied_status', [JobLists::HIRED])
                ->where('job_lists.deleted_at', NULL)
                ->where('recruiter_offices.user_id', Auth::user()->id);
        
        $obj->select('job_lists.id','job_lists.updated_at','job_lists.applied_status', 'jobseeker_profiles.first_name', 
                    'jobseeker_profiles.last_name', 'jobseeker_profiles.profile_pic', 
                'job_lists.seeker_id as seeker_id', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type');

        $obj->leftjoin('job_ratings', function($query) {
                    $query->on('job_ratings.seeker_id', '=', 'job_lists.seeker_id');
            })
            ->leftjoin('favourites',function($query){
                $query->on('favourites.seeker_id','=','job_lists.seeker_id')
                    ->where('favourites.recruiter_id',Auth::user()->id);
            });

        $obj->addSelect('favourites.seeker_id as is_favourite')
                ->leftJoin('temp_job_dates','job_lists.temp_job_id','=','temp_job_dates.id')
                ->groupby('job_lists.seeker_id');
       
        $data = $obj->orderby('job_lists.applied_status', 'desc')
                ->get();
        return $data->toArray();
    }

}
