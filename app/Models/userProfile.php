<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Helpers\apiResponse;

class UserProfile extends Model {

    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    const JOBSEEKER_VERIFY_DEFAULT = 0;
    const JOBSEEKER_VERIFY_APPROVED = 1;
    const JOBSEEKER_VERIFY_REJECT = 2;
    
    protected $fillable = [
        'user_id', 'first_name', 'last_name','preferred_job_location_id', 'preferred_location_name',
        'job_titile_id', 'jobtitle_name', 'license_number', 'state', 'preferred_job_location_id',
        'is_job_seeker_verified', 'about_me', 'profile_pic', 'dental_state_board'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public static function getUserProfile($userId)
    {
        $return = [];
        
        
        $userModel = static::select('jobseeker_profiles.id', 
                                    'jobseeker_profiles.user_id',
                                    'jobseeker_profiles.first_name',
                                    'jobseeker_profiles.last_name',
                                    'jobseeker_profiles.zipcode',
                                    'jobseeker_profiles.latitude',
                                    'jobseeker_profiles.longitude', 
                                    'jobseeker_profiles.preferred_job_location',
                                    'jobseeker_profiles.preferred_job_location_id',
                                    'preferred_job_locations.preferred_location_name',
                                    'jobseeker_profiles.preferred_city',
                                    'jobseeker_profiles.preferred_state',
                                    'jobseeker_profiles.preferred_country',
                                    'jobseeker_profiles.job_titile_id',
                                    'job_titles.jobtitle_name',
                                    'jobseeker_profiles.profile_pic',
                                    'jobseeker_profiles.dental_state_board', 
                                    'jobseeker_profiles.license_number',
                                    'jobseeker_profiles.state',
                                    'jobseeker_profiles.signup_source',
                                    'jobseeker_profiles.preferred_job_location_id',
                                    'jobseeker_profiles.is_completed',
                                    'jobseeker_profiles.is_job_seeker_verified',
                                    'jobseeker_profiles.about_me')
                    ->leftjoin('job_titles', 'job_titles.id', 'jobseeker_profiles.job_titile_id')
                    ->leftjoin('preferred_job_locations', 'preferred_job_locations.id', 'jobseeker_profiles.preferred_job_location_id')
                    ->where('jobseeker_profiles.user_id', $userId)
                    ->first();
        
        if($userModel) {
            $return = $userModel->toArray();
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
        $jobSeekerModel = static::select('is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday',
                                    'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday')
                                ->where('user_id', $userId)->first();
        
        if($jobSeekerModel) {
            $list['calendarAvailability'] = $jobSeekerModel->toArray();
            $tempAvailability = JobSeekerTempAvailability::select('temp_job_date')
                                    ->where('user_id', $userId)
                                    ->whereBetween('temp_job_date', [$calendarStartDate, $calendarEndDate])
                                    ->get();
            if($tempAvailability) {
                foreach($tempAvailability as $value) {
                    $list['tempDatesAvailability'][] = $value['temp_job_date'];
                }
            }
        }
        return $list;
    }
    
    public static function checkIfAvailabilitySet($userId) {
        $checkAvailabilityStatus = 0;
        $userAvailability = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select('users.id')
                        ->where('user_groups.group_id', 3)
                        ->where('users.id', $userId)
                        ->where(function($query) {
                            $query->orWhere('is_fulltime',1)
                                ->orWhere('is_parttime_monday',1)
                                ->orWhere('is_parttime_tuesday',1)
                                ->orWhere('is_parttime_wednesday',1)
                                ->orWhere('is_parttime_thursday',1)
                                ->orWhere('is_parttime_friday',1)
                                ->orWhere('is_parttime_saturday',1)
                                ->orWhere('is_parttime_sunday',1);
                        })
                        ->first();
        
        if($userAvailability) {
            $checkAvailabilityStatus = 1;
        }
        
        $tempAvailableUsers = JobSeekerTempAvailability::where('user_id',$userId)->get();
        if($tempAvailableUsers) {
            $checkAvailabilityStatus = 1;
        }
        
        return $checkAvailabilityStatus = 1;
    }

}
