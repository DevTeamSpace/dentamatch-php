<?php
namespace App\Helpers;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\WorkExperience;
use App\Models\JobSeekerSchooling;
use App\Models\JobSeekerSkills;
use App\Models\JobSeekerAffiliation;
use App\Models\JobseekerCertificates;

class apiResponse {

    public static function responseError($message = '', $data = array()) {
        $key = !empty($data) ? key($data) : '';
        $response = array(
            'status' => 0,
            'message' => $message
        );
        if (!empty($key)) {
            $response[$key] = (object) $data[$key];
        }
        return static::convertToCamelCase($response);
    }
    
    public static function convertToCamelCase($array) {
        $converted_array = [];
        foreach ($array as $old_key => $value) {
            if (is_array($value)) {
                $value = static::convertToCamelCase($value);
            } else if (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $value = $value->toArray();
                } else {
                    $value = (array) $value;
                }


                $value = static::convertToCamelCase($value);
            }
            $converted_array[camel_case($old_key)] = $value;
        }

        return $converted_array;
    }
    
    public static function customJsonResponse($status, $statusCode, $message = '', $data = array()) {
        $response = array(
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        );
        if(is_array($data) && count($data) > 0){
            $response['result'] = (object) $data;
        }
        return json_encode($response);
    }
    
    public static function customJsonResponseObject($status, $statusCode, $message = '',$key = '', $obj = null) {
        $response = array(
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        );
        if(!is_null($obj) && $key != ""){
            $response['result'][$key] = $obj;
        }
        return json_encode($response);
    }
    
    public static function loginUserId($accesstoken) {
        $user = Device::select('user_id')->where('user_token',$accesstoken)->first();
        if($user){
            return $user->user_id;
        }else{
            return 0;
        }
    }
    public static function getThumbImage($image){
        $profilePic = "";
            if($image && $image != ""){
                $width = 150;
                $height = 150;
                $profilePic  = url("image/" . $width . "/" . $height . "/?src=" .$image);
            }
        return $profilePic;
    }
    
    public static function chkProfileComplete($userId){
        
        $userProfileModel = UserProfile::getUserProfile($userId);
        $userWorkExperience = WorkExperience::getWorkExperienceList($userId);
        $schooling = JobSeekerSchooling::getJobSeekerSchooling($userId);
        $otherSchooling = JobSeekerSchooling::getJobseekerOtherSchooling($userId);
        $schooling = array_merge($schooling, $otherSchooling);
        $skills = JobSeekerSkills::getJobSeekerSkills($userId);
        $otherSkills = JobSeekerSkills::getJobseekerOtherSkills($userId);
        $skills = array_merge($skills, $otherSkills);
        $affiliations = JobSeekerAffiliation::getJobSeekerAffiliation($userId);
        $jobSeekerCertifications = JobseekerCertificates::getJobSeekerCertificates($userId);
        $chkProfileStatus = 0;
        $completionStatus = 0;
        $workExperienceStatus = ($userWorkExperience['total'] > 0) ? 1 : 0;
        $schoolingStatus = (count($schooling) > 0) ? 1 : 0;
        $skillStatus = (count($skills) > 0) ? 1 : 0;
        $affiliationStatus = (count($affiliations) > 0) ? 1 : 0;
        $certificationStatus = (count($jobSeekerCertifications) > 0) ? 1 : 0;
        if($userProfileModel['job_titile_id'] > 0 && 
                $userProfileModel['profile_pic'] != "" && 
                $userProfileModel['dental_state_board'] != "" && 
                $userProfileModel['license_number'] != "" && 
                $userProfileModel['state'] != "" && 
                $userProfileModel['about_me'] != "" 
                ){
            $chkProfileStatus = 1;
        }
        
        if($chkProfileStatus == 1 && $workExperienceStatus == 1 && $schoolingStatus == 1 && $skillStatus == 1 && $affiliationStatus == 1 && $certificationStatus == 1){
            $completionStatus = 1;
        }
        $userProfile = UserProfile::where('user_id', $userId)->first();
        $userProfile->is_completed = $completionStatus;
        $userProfile->save();
        
    }
}

