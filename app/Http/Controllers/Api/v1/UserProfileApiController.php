<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\SeekerVerifiedStatus;
use App\Http\Controllers\Controller;
use App\Mail\AdminVerifyJobseeker;
use App\Mail\PendingEmailVerification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserProfile;
use App\Helpers\ApiResponse;
use App\Repositories\File\FileRepositoryS3;
use App\Models\WorkExperience;
use App\Models\JobSeekerSchooling;
use App\Models\JobSeekerSkills;
use App\Models\JobSeekerAffiliation;
use App\Models\JobseekerCertificates;
use App\Models\Certifications;
use App\Models\JobTitles;

class UserProfileApiController extends Controller
{
    use FileRepositoryS3;

    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Method for change password
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
            'oldPassword'        => 'required|max:255',
            'newPassword'        => 'required|min:6|max:255',
            'confirmNewPassword' => 'required|min:6|max:255'
        ]);
        $reqData = $request->all();
        $userId = $request->apiUserId;

        $userModel = User::where('id', $userId)->first();
        if (!Hash::check($reqData['oldPassword'], $userModel->password)) {
            $response = ApiResponse::errorResponse(trans("messages.incorrect_old_password"));
        } else if ($reqData['newPassword'] !== $reqData['confirmNewPassword']) {
            $response = ApiResponse::errorResponse(trans("messages.mismatch_pw_cpw"));
        } else if (!empty($userModel)) {
            $userModel->password = bcrypt($reqData['newPassword']);
            $userModel->save();
            $response = ApiResponse::successResponse(trans("messages.password_update_successful"));
        }

        return $response;
    }

    /**
     * Upload User Image
     * @param Request $request
     * @return Response
     */
    public function postUploadImage(Request $request)
    {
        $userId = $request->apiUserId;
        $filename = $this->generateFilename($request->type);
        $response = $this->uploadFileToAWS($request, $filename, 'image');
        if ($response['res']) {
            if ($request->type == 'profile_pic') {
                UserProfile::where('user_id', $userId)->update(['profile_pic' => $response['file']]);
            } else {
                UserProfile::where('user_id', $userId)->update(['dental_state_board' => $response['file']]);
            }
            ApiResponse::chkProfileComplete($userId);

            $url['img_url'] = ApiResponse::getThumbImage($response['file']);
            $response = ApiResponse::successResponse(trans("messages.image_upload_success"), $url);
        } else {
            $response = ApiResponse::errorResponse(trans("messages.prob_upload_image"));
        }
        return $response;
    }

    /**
     * Method to update license
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function putUpdateLicense(Request $request)
    {
        $validateKeys = [
            'jobTitleId' => 'required',
            'aboutMe'    => 'required'
        ];
        $mappedSkillsArray = [];
        $isJobSeekerVerified = 1;
        $isLicenseRequired = 0;
        $jobTitleModel = JobTitles::where('id', $request->jobTitleId)->first();
        if ($jobTitleModel) {
            if ($jobTitleModel->mapped_skills_id == "") {
                $mappedSkillsArray = [];
            } else {
                $mappedSkills = $jobTitleModel->mapped_skills_id;
                $mappedSkillsArray = explode(",", $mappedSkills);
            }
            if ($jobTitleModel->is_license_required) {
                $validateKeys['license'] = 'required';
                $validateKeys['state'] = 'required';
                $isLicenseRequired = 1;
            }
        }
        $this->validate($request, $validateKeys);
        $userId = $request->apiUserId;
        $userProfileModel = UserProfile::where('user_id', $userId)->first();
        if ($isLicenseRequired && ($userProfileModel->license_number != $request->license || $userProfileModel->state != $request->state)) {
            if (!empty($request->license) && !empty($request->state)) {
                $userLicenData = User::getUser($userId);
                $userName = $userLicenData['first_name'] . ' ' . $userLicenData['last_name'];
                $userEmail = $userLicenData['email'];
                $adminEmail = env('ADMIN_EMAIL');
                Mail::to($adminEmail)->queue(new AdminVerifyJobseeker($userName, $userEmail));
            }
            $isJobSeekerVerified = 0;
        }
        $userProfileModel->about_me = $request->aboutMe;
        $userProfileModel->license_number = $request->license;
        $userProfileModel->state = $request->state;
        $userProfileModel->is_job_seeker_verified = $isJobSeekerVerified;

        if (($request->jobTitleId != "") && ($request->jobTitleId > 0)) {
            $userProfileModel->job_titile_id = $request->jobTitleId;
        }

        $userProfileModel->save();

        if (!empty($mappedSkillsArray)) {
            JobSeekerSkills::addJobSeekerSkills($userId, $mappedSkillsArray);
        }
        ApiResponse::chkProfileComplete($userId);

        $userData['userDetails'] = User::getUser($userId);
        return ApiResponse::successResponse(trans("messages.data_saved_success"), $userData);
    }

    /**
     * Method to update or insert About Me detail of a user
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postAboutMe(Request $request)
    {
        $this->validate($request, [
            'aboutMe' => 'required',
        ]);

        $userId = $request->apiUserId;
        UserProfile::where('user_id', $userId)->update(['about_me' => $request->aboutMe, 'is_completed' => 1]);
        ApiResponse::chkProfileComplete($userId);
        return ApiResponse::successResponse(trans("messages.profile_update_success"));
    }

    /**
     * Method to fetch About Me data of a user
     * @param Request $request
     * @return Response
     */
    public function getAboutMe(Request $request)
    {
        $userId = $request->apiUserId;
        $userProfileModel = UserProfile::where('user_id', $userId)->first();
        $data['list']['aboutMe'] = $userProfileModel->about_me;
        $data['list']['dentalStateBoard']['imageUrl'] = !empty($userProfileModel->dental_state_board) ? ApiResponse::getThumbImage($userProfileModel->dental_state_board) : null;

        $licenceData = ['license_number' => $userProfileModel->license_number, 'state' => $userProfileModel->state];
        $data['list']['licence'] = $licenceData;

        return ApiResponse::successResponse(trans("messages.about_me_list"), $data);
    }

    /**
     * Method to fetch user profile
     * @param Request $request
     * @return Response
     */
    public function getUserProfile(Request $request)
    {
        $userId = $request->apiUserId;
        $certificationData = [];
        $skillData = [];
        $userProfileModel = UserProfile::getUserProfile($userId);
        $userWorkExperience = WorkExperience::getWorkExperienceList($userId);
        $schooling = JobSeekerSchooling::getJobSeekerSchooling($userId);
        $otherSchooling = JobSeekerSchooling::getJobseekerOtherSchooling($userId);
        $schooling = array_merge($schooling, $otherSchooling);
        $skills = JobSeekerSkills::getJobSeekerSkills($userId);
        $otherSkills = JobSeekerSkills::getJobseekerOtherSkills($userId);
        $skills = array_merge($skills, $otherSkills);
        if (!empty($skills)) {
            foreach ($skills as $skillValue) {
                $skillData[$skillValue['parentId']]['id'] = $skillValue['parentId'];
                $skillData[$skillValue['parentId']]['skillName'] = $skillValue['skillsName'];
                $skillData[$skillValue['parentId']]['children'][] = [
                    'id'         => $skillValue['childId'],
                    'skillName'  => $skillValue['skillsChildName'],
                    'otherSkill' => $skillValue['otherSkills']
                ];
            }
        }
        $jobTitle = JobTitles::where('is_active', 1)->orderBy('id', 'asc')->get()->toArray();
        $affiliations = JobSeekerAffiliation::getJobSeekerAffiliation($userId);
        $jobSeekerCertifications = JobseekerCertificates::getJobSeekerCertificates($userId);
        $allCertification = Certifications::getAllCertificates();
        if (!empty($allCertification)) {
            foreach ($allCertification as $key => $value) {
                $certificationData[$key] = $value;
                $certificationData[$key]['imagePath'] = !empty($jobSeekerCertifications[$key]) ? $jobSeekerCertifications[$key]['image_path'] : null;
                $certificationData[$key]['validityDate'] = !empty($jobSeekerCertifications[$key]) ? $jobSeekerCertifications[$key]['validity_date'] : null;
            }
        }

        $data['user'] = $userProfileModel;
        $data['dentalStateBoard']['imageUrl'] = $userProfileModel['dental_state_board'];

        $licenceData = ['license_number' => $userProfileModel['license_number'], 'state' => $userProfileModel['state']];
        $data['licence'] = $licenceData;

        $data['school'] = $schooling;
        $data['skills'] = array_values($skillData);
        $data['affiliations'] = $affiliations;
        $data['certifications'] = array_values($certificationData);
        $data['workExperience'] = $userWorkExperience;
        $data['joblists'] = $jobTitle;

        return ApiResponse::successResponse(trans("messages.user_profile_list"), $data);
    }

    /**
     * Description : Update Job Seeker Profile
     * Method : updateUserProfile
     * formMethod : PUT
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateUserProfile(Request $request)
    {
        $this->validate($request, [
            'firstName'              => 'required',
            'lastName'               => 'required',
            'jobTitileId'            => 'required',
            'preferredJobLocationId' => 'required',
            'aboutMe'                => 'required',
        ]);

        $reqData = $request->all();
        $isJobSeekerVerified = 1;
        $isLicenseRequired = 0;
        $jobTitleModel = JobTitles::where('id', $reqData['jobTitileId'])->first();
        if (is_object($jobTitleModel) && $jobTitleModel->is_license_required) {
            $this->validate($request, ['licenseNumber' => 'required', 'state' => 'required']);
            $isLicenseRequired = 1;
        }

        $userId = $request->apiUserId;
        $userProfile = UserProfile::where('user_id', $userId)->first();
        if ($userProfile->is_job_seeker_verified) {
            if ($isLicenseRequired && ((isset($reqData['licenseNumber']) && $userProfile->license_number != $reqData['licenseNumber']) || (isset($reqData['state']) && $userProfile->state != $reqData['state']))) {
                if (!empty($reqData['licenseNumber']) && !empty($reqData['state'])) {
                    $userLicenData = User::getUser($userId);
                    $userName = $userLicenData['first_name'] . ' ' . $userLicenData['last_name'];
                    $userEmail = $userLicenData['email'];
                    $adminEmail = env('ADMIN_EMAIL');
                    Mail::to($adminEmail)->queue(new AdminVerifyJobseeker($userName, $userEmail));
                }
                $isJobSeekerVerified = 0;
            }
        } else if ($isLicenseRequired && (isset($reqData['licenseNumber']) && isset($reqData['state']))) {
            if (!empty($reqData['licenseNumber']) && !empty($reqData['state'])) {
                $userLicenData = User::getUser($userId);
                $userName = $userLicenData['first_name'] . ' ' . $userLicenData['last_name'];
                $userEmail = $userLicenData['email'];
                $adminEmail = env('ADMIN_EMAIL');
                Mail::to($adminEmail)->queue(new AdminVerifyJobseeker($userName, $userEmail));
            }
            $isJobSeekerVerified = 0;
        }

        if ($userProfile->is_job_seeker_verified != SeekerVerifiedStatus::APPROVED && $userProfile->job_titile_id != $jobTitleModel->id) {
            $mappedSkillsArray = ($jobTitleModel->mapped_skills_id == '') ? [] : explode(",", $jobTitleModel->mapped_skills_id);
            if (!empty($mappedSkillsArray)) {
                JobSeekerSkills::where('user_id', $userId)->forceDelete();
                JobSeekerSkills::addJobSeekerSkills($userId, $mappedSkillsArray);
            }
        }
        $userProfile->first_name = $reqData['firstName'];
        $userProfile->last_name = $reqData['lastName'];
        $userProfile->preferred_job_location_id = $reqData['preferredJobLocationId'];
        $userProfile->job_titile_id = $reqData['jobTitileId'];
        $userProfile->about_me = $reqData['aboutMe'];
        $userProfile->license_number = isset($reqData['licenseNumber']) ? $reqData['licenseNumber'] : null;
        $userProfile->state = isset($reqData['state']) ? $reqData['state'] : null;
        $userProfile->is_job_seeker_verified = $isJobSeekerVerified;
        $userProfile->save();

        ApiResponse::chkProfileComplete($userId);
        $message = trans("messages.user_profile_updated");
        return ApiResponse::successResponse($message);
    }

    /**
     * Description : Update Job Seeker location
     * Method : updateUserLocationUpdate
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateUserLocationUpdate(Request $request)
    {
        $this->validate($request, [
            'preferedLocation' => 'required',
            'latitude'         => 'required',
            'longitude'        => 'required',
            'zipCode'          => 'required',
        ]);
        $reqData = $request->all();
        $userId = $request->apiUserId;
        $userProfile = UserProfile::where('user_id', $userId)->first();
        $userProfile->zipcode = $reqData['zipCode'];
        $userProfile->latitude = $reqData['latitude'];
        $userProfile->longitude = $reqData['longitude'];
        $userProfile->preferred_job_location = $reqData['preferedLocation'];
        $userProfile->preferred_city = $reqData['preferredCity'];
        $userProfile->preferred_state = $reqData['preferredState'];
        $userProfile->preferred_country = $reqData['preferredCountry'];
        $userProfile->save();
        return ApiResponse::successResponse(trans("messages.location_update_success"));
    }

    /**
     * Method to get seeker verified status
     * @param Request $request
     * @return Response
     */
    public function getIsUserVerified(Request $request)
    {
        $userId = $request->apiUserId;

        $userModel = User::isUserEmailVerified($userId);
        if ($userModel->is_verified) {
            return ApiResponse::successResponse(trans("messages.data_retrieved_successfully"), ['isVerified' => 1]);
        } else {
            $url = url("/verification-code/" . $userModel->verification_code);
            $name = $userModel->first_name;
            $email = $userModel->email;
            Mail::to($email)->queue(new PendingEmailVerification($name, $url));
            return ApiResponse::successResponse("Email verification link has been sent to $email.", ['isVerified' => 0]);
        }
    }
}
