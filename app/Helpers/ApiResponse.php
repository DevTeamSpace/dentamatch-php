<?php
namespace App\Helpers;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\JobSeekerSkills;
use App\Models\JobTitles;

class ApiResponse {
    
    /**
     * @param  message array  $data
     * @return error response
     */
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
    
    /**
     * @param   array  $data
     * @return camelcase data
     */
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
    
    /**
     * @param  status statuscode message array  $data
     * @return jsonencoded response
     */
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
    
    /**
     * @param  status statuscode message key array  $data
     * @return jsonencoded response
     */
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
    
    /**
     * @param  accesstoken
     * @return userid or 0
     */
    public static function loginUserId($accesstoken) {
        $user = Device::select('user_id')->where('user_token',$accesstoken)->first();
        if($user){
            return $user->user_id;
        }else{
            return 0;
        }
    }
    
    /**
     * @param  image
     * @return thumburl
     */
    public static function getThumbImage($image){
        $profilePic = "";
            if($image && $image != ""){
                $width = 150;
                $height = 150;
                $profilePic  = url("image/" . $width . "/" . $height . "/?src=" .$image);
            }
        return $profilePic;
    }
    
    
    /**
     * Update user profile status 
     * @param  userId
     */
    public static function chkProfileComplete($userId){
        
        $userProfileModel = UserProfile::getUserProfile($userId);
        
        $skills = JobSeekerSkills::getJobSeekerSkills($userId);
        $otherSkills = JobSeekerSkills::getJobseekerOtherSkills($userId);
        $skills = array_merge($skills, $otherSkills);
        $chkProfileStatus = 0;
        $completionStatus = 0;
        $skillStatus = (count($skills) > 0) ? 1 : 0;
        $checkLicenseAndStateVerified = 1;
        if(!empty($userProfileModel['job_titile_id'])) {
            $chkProfileStatus = 1;
            $jobTitleModel = JobTitles::getTitle($userProfileModel['job_titile_id']);
            if($jobTitleModel && $jobTitleModel['is_license_required'] == 1 && (empty($userProfileModel['license_number']) || empty($userProfileModel['state']))) {
                $checkLicenseAndStateVerified = 0;
            }
        } else {
            $checkLicenseAndStateVerified = 0;
        }
        
        $checkAvailabilitySet = UserProfile::checkIfAvailabilitySet($userId);
      
        if($chkProfileStatus == 1 && $checkLicenseAndStateVerified == 1 && $skillStatus == 1 && $checkAvailabilitySet == 1){
            $completionStatus = 1;
        }
        
        $userProfile = UserProfile::where('user_id', $userId)->first();
        $userProfile->is_completed = $completionStatus;
        $userProfile->save();
        $userProfile->fresh();
        
    }
}

