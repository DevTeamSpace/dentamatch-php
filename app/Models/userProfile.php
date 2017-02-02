<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\apiResponse;

class UserProfile extends Model {

    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public static function getUserProfile($userId)
    {
        $return = [];
        $s3Url = env('AWS_URL');
        $s3Bucket = env('AWS_BUCKET');
        
        $userModel = static::select('jobseeker_profiles.id', 'jobseeker_profiles.user_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'jobseeker_profiles.zipcode', 'jobseeker_profiles.latitude', 'jobseeker_profiles.longitude', 'jobseeker_profiles.preferred_job_location', 
                            'jobseeker_profiles.job_titile_id',  'jobseeker_profiles.profile_pic', 'jobseeker_profiles.dental_state_board', 'jobseeker_profiles.license_number', 'jobseeker_profiles.state', 'jobseeker_profiles.about_me')      
                    ->where('jobseeker_profiles.user_id', $userId)
                    ->first();
        
        if($userModel) {
            $return = $userModel->toArray();
            $title = "";   
            if($return['job_titile_id']){
                $jobTitle = JobTitles::select('jobtitle_name')->where('id',$return['job_titile_id'])->first()->toArray();
                $title = $jobTitle['jobtitle_name'];
            }else{
                $return['job_titile_id'] = 0;
            }
            $return['job_title'] = $title;
            $return['profile_pic'] = apiResponse::getThumbImage($return['profile_pic']);
            if(($return['dental_state_board']) && $return['dental_state_board'] != ""){
                $return['dental_state_board'] = apiResponse::getThumbImage($return['dental_state_board']);
            }else{
                $return['dental_state_board'] = "";
            }
            
        }
        return $return;
    }
    
    public static function getAvailability($userId, $calendarStartDate, $calendarEndDate)
    {
        $list = ['calendarAvailability'=>[], 'tempDatesAvailability'=>[]];
        $tempJobsAvaibility =[];
        $tempJobsHired=[];
        $jobSeekerModel = static::select('is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday',
                                    'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday')
                                ->where('user_id', $userId)->first();
        
        if($jobSeekerModel) {
            $list['calendarAvailability'] = $jobSeekerModel->toArray();
            
            $tempHired = JobLists::select('temp_job_dates.job_date')
                            ->join('temp_job_dates', 'temp_job_dates.recruiter_job_id', 'job_lists.recruiter_job_id')
                            ->where('seeker_id', $userId)
                            ->where('applied_status', 4)
                            //->whereBetween('job_date', [$calendarStartDate, $calendarEndDate])
                            ->get();
            if($tempHired) {
                foreach($tempHired as $value) {
                    $tempJobsHired[] = $value->job_date;
                }
            }
            
            $tempAvailability = JobSeekerTempAvailability::select('temp_job_date')
                                    ->where('user_id', $userId)
                                    ->whereBetween('temp_job_date', [$calendarStartDate, $calendarEndDate])
                                    ->get();
            if($tempAvailability) {
                foreach($tempAvailability as $value) {
                    if(!in_array($value->temp_job_date, $tempJobsHired)) {
                        $tempJobsAvaibility[] = $value->temp_job_date;
                    }
                }
            }
            
            $list['tempDatesAvailability'] = $tempJobsAvaibility; 
            
        }
        return $list;
    }

}
