<?php

namespace App\Helpers;

use App\Models\UserProfile;
use App\Models\JobSeekerSkills;
use App\Models\JobTitles;
use Illuminate\Http\Response;

class ApiResponse
{
    /**
     * @param  string $image
     * @return string
     */
    public static function getThumbImage($image)
    {
        $profilePic = "";
        if ($image && $image != "") {
            $width = 150;
            $height = 150;
            $profilePic = url("image/" . $width . "/" . $height . "/?src=" . $image);
        }
        return $profilePic;
    }


    /**
     * Update user profile status
     * @param int $userId
     */
    public static function chkProfileComplete($userId)
    {
        $userProfileModel = UserProfile::getUserProfile($userId);

        $skills = JobSeekerSkills::getJobSeekerSkills($userId);
        $otherSkills = JobSeekerSkills::getJobseekerOtherSkills($userId);
        $skills = array_merge($skills, $otherSkills);
        $chkProfileStatus = 0;
        $completionStatus = 0;
        $skillStatus = (count($skills) > 0) ? 1 : 0;
        $checkLicenseAndStateVerified = 1;
        if (!empty($userProfileModel['job_titile_id'])) {
            $chkProfileStatus = 1;
            $jobTitleModel = JobTitles::getTitle($userProfileModel['job_titile_id']);
            if ($jobTitleModel && $jobTitleModel['is_license_required'] == 1 && (empty($userProfileModel['license_number']) || empty($userProfileModel['state']))) {
                $checkLicenseAndStateVerified = 0;
            }
        } else {
            $checkLicenseAndStateVerified = 0;
        }

        $checkAvailabilitySet = UserProfile::checkIfAvailabilitySet($userId);

        if ($chkProfileStatus == 1 && $checkLicenseAndStateVerified == 1 && $skillStatus == 1 && $checkAvailabilitySet == 1) {
            $completionStatus = 1;
        }

        $userProfile = UserProfile::where('user_id', $userId)->first();
        $userProfile->is_completed = $completionStatus;
        $userProfile->save();
        $userProfile->fresh();

    }


    /**
     * @param string $message
     * @param array $data
     * @return Response response
     */
    public static function successResponse($message = '', $data = [])
    {
        return self::customJsonResponse(1, 200, $message, $data);
    }

    /**
     * @param string $message
     * @param array $data
     * @return Response
     */
    public static function errorResponse($message = '', $data = [])
    {
        $key = !empty($data) ? key($data) : '';
        $response = [
            'status'  => 0,
            'message' => $message
        ];
        if (!empty($key)) {
            $response[$key] = (object)$data[$key];
        }
        return response()->json(self::convertToCamelCase($response));
    }

    /**
     * @return Response response
     */
    public static function noDataResponse()
    {
        return self::customJsonResponse(0, 201, trans("messages.no_data_found"));
    }

    /**
     * @param int $status
     * @param int $statusCode
     * @param string $message
     * @param array $data
     * @return Response
     */
    public static function customJsonResponse($status, $statusCode, $message = '', $data = [])
    {
        $response = [
            'status'     => $status,
            'statusCode' => $statusCode,
            'message'    => $message,
        ];

        if ($data) {
            $response['result'] = self::convertToCamelCase($data);
        }
        return response()->json($response);
    }

    /**
     * @param array $array
     * @return array
     */
    private static function convertToCamelCase($array)
    {
        $converted_array = [];
        foreach ($array as $old_key => $value) {
            if (is_array($value)) {
                $value = static::convertToCamelCase($value);
            } else if (is_object($value)) {
                if (method_exists($value, 'toArray')) {
                    $value = $value->toArray();
                } else {
                    $value = (array)$value;
                }


                $value = static::convertToCamelCase($value);
            }
            $converted_array[camel_case($old_key)] = $value;
        }

        return $converted_array;
    }
}

