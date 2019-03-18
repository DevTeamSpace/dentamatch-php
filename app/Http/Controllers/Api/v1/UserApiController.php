<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Mail\UserActivation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\SearchFilter;
use App\Models\JobSeekerTempAvailability;
use App\Helpers\ApiResponse;

class UserApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog'], ['only' => ['deleteSignOut']]);
    }

    /**
     * Description : Signup User
     * Method : postSignup
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postSignup(Request $request)
    {
        $this->validate($request, [
            'deviceId'               => 'required',
            'deviceType'             => 'required',
            'firstName'              => 'required',
            'lastName'               => 'required',
            'email'                  => 'required',
            'password'               => 'required',
            'preferredJobLocationId' => 'required',
        ]);

        $reqData = $request->all();
        $mappedSkillsArray = [];

        $userExists = User::with('userGroup')->where('email', $reqData['email'])->first();
        if ($userExists) {
            if (isset($userExists->userGroup) && !empty($userExists->userGroup)) {
                if ($userExists->userGroup->group_id == UserGroup::RECRUITER) {
                    $msg = trans("messages.already_register_as_recruiter");
                } else {
                    $msg = trans("messages.user_exist_same_email");
                }
            }
            return ApiResponse::errorResponse($msg);
        }

        $uniqueCode = uniqid();
        $user = [
            'email'             => $reqData['email'],
            'password'          => bcrypt($reqData['password']),
            'verification_code' => $uniqueCode,
        ];
        $userDetails = User::create($user);
        $userGroupModel = new UserGroup();
        $userGroupModel->group_id = UserGroup::JOBSEEKER;
        $userGroupModel->user_id = $userDetails->id;
        $userGroupModel->save();

        $userProfileModel = new UserProfile();

        $userProfileModel->user_id = $userDetails->id;
        $userProfileModel->first_name = $reqData['firstName'];
        $userProfileModel->last_name = $reqData['lastName'];
        $userProfileModel->preferred_job_location_id = $reqData['preferredJobLocationId'];
        $userProfileModel->is_fulltime = config('constants.AutoAvailabilityFlag');
        $userProfileModel->is_parttime_monday = config('constants.AutoAvailabilityFlag');
        $userProfileModel->is_parttime_tuesday = config('constants.AutoAvailabilityFlag');
        $userProfileModel->is_parttime_wednesday = config('constants.AutoAvailabilityFlag');
        $userProfileModel->is_parttime_thursday = config('constants.AutoAvailabilityFlag');
        $userProfileModel->is_parttime_friday = config('constants.AutoAvailabilityFlag');
        $userProfileModel->is_parttime_saturday = config('constants.NonAvailabilityFlag');
        $userProfileModel->is_parttime_sunday = config('constants.NonAvailabilityFlag');
        $userProfileModel->profile_pic = config('constants.defaultProfileImage');

        $userProfileModel->save();

        $deviceModel = new Device();
        $reqData['deviceOs'] = isset($reqData['deviceOs']) ? $reqData['deviceOs'] : '';
        $reqData['appVersion'] = isset($reqData['appVersion']) ? $reqData['appVersion'] : '';
        $deviceToken = isset($reqData['deviceToken']) ? $reqData['deviceToken'] : "";
        $deviceModel->registerDevice(
            $reqData['deviceId'], $userDetails->id, $deviceToken,
            $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']
        );

        $current = strtotime(date('Y-m-d'));
        $last = strtotime(date('Y-m-d') . " +60 days");
        JobSeekerTempAvailability::addTempDateAvailability($userDetails->id, $current, $last);

        $url = url("/verification-code/$uniqueCode");
        $name = $reqData['firstName'];
        $email = $reqData['email'];
        Mail::to($email)->queue(new UserActivation($name, $url));
        $userData['userDetails'] = User::getUser($userDetails->id);
        return ApiResponse::successResponse(trans("messages.user_registration_successful"), $userData);

    }

    /**
     * Login user
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postSignIn(Request $request)
    {
        $this->validate($request, [
            'deviceId'   => 'required',
            'deviceType' => 'required',
            'email'      => 'required|email',
            'password'   => 'required',
        ]);

        $reqData = $request->all();
        $userAttempt = Auth::attempt(['email' => $reqData['email'], 'password' => $reqData['password']]);
        $userId = ($userAttempt == true) ? Auth::user()->id : null;
        if ($userId > 0) {
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->leftjoin('job_titles', 'jobseeker_profiles.job_titile_id', '=', 'job_titles.id')
                ->leftjoin('preferred_job_locations', 'preferred_job_locations.id', '=', 'jobseeker_profiles.preferred_job_location_id')
                ->select(
                    'user_groups.group_id',
                    'users.email', 'users.id', 'users.is_active',
                    'jobseeker_profiles.first_name',
                    'jobseeker_profiles.last_name',
                    'jobseeker_profiles.profile_pic',
                    'jobseeker_profiles.zipcode', 'jobseeker_profiles.is_job_seeker_verified',
                    'jobseeker_profiles.preferred_job_location', 'jobseeker_profiles.state',
                    'jobseeker_profiles.latitude', 'jobseeker_profiles.license_number',
                    'jobseeker_profiles.longitude', 'jobseeker_profiles.preferred_city',
                    'jobseeker_profiles.preferred_state', 'jobseeker_profiles.preferred_country',
                    'jobseeker_profiles.about_me', 'jobseeker_profiles.preferred_job_location_id',
                    'users.is_verified', 'jobseeker_profiles.is_completed', 'jobseeker_profiles.job_titile_id',
                    'preferred_job_locations.preferred_location_name', 'job_titles.jobtitle_name'
                )
                ->where('users.id', $userId)
                ->first();
            if ($userData['group_id'] == UserGroup::JOBSEEKER) {
                if ($userData['is_verified'] == 0) {
                    $response = ApiResponse::errorResponse(trans("messages.user_registration_successful_old"));
                } else if ($userData['is_verified'] == 1 && $userData['is_active'] == 1) {
                    $device = Device::where('user_id', $userId)->orWhere('device_id', $reqData['deviceId'])->first();
                    $reqData['deviceOs'] = isset($reqData['deviceOs']) ? $reqData['deviceOs'] : '';
                    $reqData['appVersion'] = isset($reqData['appVersion']) ? $reqData['appVersion'] : '';
                    if (is_object($device) && ($device->device_id != $reqData['deviceId'] || $device->user_id != $userId)) {
                        Device::where('device_id', $device->device_id)->orWhere('user_id', $userId)->delete();
                        $deviceModel = new Device();
                        $userToken = $deviceModel->registerDevice($reqData['deviceId'], $userId, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                    } else {
                        $deviceModel = new Device();
                        $userToken = $deviceModel->registerDevice($reqData['deviceId'], $userId, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                    }
                    $imgUrl = "";
                    if ($userData['profile_pic']) {
                        $imgUrl = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $userData['profile_pic'];
                    }
                    $userArray['userDetails'] = [
                        'id'                     => $userData['id'],
                        'email'                  => $userData['email'],
                        'firstName'              => $userData['first_name'],
                        'lastName'               => $userData['last_name'],
                        'imageUrl'               => $imgUrl,
                        'zipCode'                => $userData['zipcode'],
                        'preferredJobLocation'   => $userData['preferred_job_location'],
                        'preferredJobLocationId' => $userData['preferred_job_location_id'],
                        'latitude'               => $userData['latitude'],
                        'longitude'              => $userData['longitude'],
                        'accessToken'            => $userToken,
                        'profileCompleted'       => $userData['is_completed'],
                        'city'                   => $userData['preferred_city'],
                        'state'                  => $userData['state'],
                        'country'                => $userData['preferred_country'],
                        'aboutMe'                => $userData['about_me'],
                        'licenseNumber'          => $userData['license_number'],
                        'preferredLocationName'  => $userData['preferred_location_name'],
                        'jobtitleName'           => $userData['jobtitle_name'],
                        'jobTitileId'            => $userData['job_titile_id'],
                        'isVerified'             => $userData['is_verified'],
                        'isJobSeekerVerified'    => $userData['is_job_seeker_verified']
                    ];
                    $searchArray = SearchFilter::getFiltersOnLogin($userId); // todo test filters
                    $response = ApiResponse::successResponse(trans("messages.user_logged_successful"), $userArray);
                } else {
                    $response = ApiResponse::errorResponse(trans("messages.user_account_not_active"));
                }
            } else {
                $response = ApiResponse::errorResponse(trans("messages.invalid_login_credentials"));
            }
        } else {
            $response = ApiResponse::errorResponse(trans("messages.invalid_login_credentials"));
        }
        return $response;

    }

    /**
     * Description : Activate Jobseeker
     * Method : getActivateJobseeker
     * formMethod : GET
     * @param $confirmation_code
     * @return Response
     */
    public function getActivateJobseeker($confirmation_code)
    {
        $is_verified = 0;
        $profile_details = UserProfile::where('verification_code', $confirmation_code)->first();
        if (is_object($profile_details) && $profile_details->is_verified == 0) {
            $update_profile = UserProfile::find($profile_details->id);
            $update_profile->is_verified = 1; // todo does it really work or used? No such field on UserProfile Model
            $update_profile->save();
            $is_verified = 1;
        }
        return view('verify-user')->with('verifyUser', $is_verified);
    }

    /**
     * Method to make forgot password  request
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function putForgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $reqData = $request->all();
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                ['users.id',
                 'user_groups.group_id',
                 'users.email',
                 'jobseeker_profiles.first_name',
                 'jobseeker_profiles.last_name',
                 'users.is_verified']
            )
            ->where('users.email', $reqData['email'])
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->first();
        if ($user) {
            if ($user->is_verified == 1) {
                $token = Password::broker()->createToken($user);
                $url = url('password/reset', ['token' => $token]);
                Mail::to($user->email)->queue(new ResetPassword($user->first_name, $url));
                $response = ApiResponse::successResponse(trans("messages.reset_pw_email_sent"));
            } else {
                $response = ApiResponse::errorResponse(trans("messages.user_account_not_active"));
            }
        } else {
            $response = ApiResponse::errorResponse(trans("messages.email_not_exists"));
        }
        return $response;
    }

    /**
     * Method to logout user from the system
     * @param Request $request
     * @return Response
     */
    public function deleteSignOut(Request $request)
    {
        Device::unRegisterAll($request->apiUserId);
        return ApiResponse::successResponse(trans("messages.user_signout"));
    }

    /**
     * Method to reset password mail by admin
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postAdminForgotPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $reqData = $request->all();
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->select([
                    'users.id',
                    'user_groups.group_id',
                    'users.email',
                    'users.is_verified']
            )
            ->where('users.email', $reqData['email'])
            ->where('user_groups.group_id', UserGroup::ADMIN)
            ->first();

        if ($user) {
            $token = Password::broker()->createToken($user);
            $url = url('password/reset', ['token' => $token]);
            Mail::to($user->email)->queue(new ResetPassword('Admin', $url));

            $response = ApiResponse::successResponse(trans("messages.reset_pw_email_sent"));
        } else {
            $response = ApiResponse::errorResponse(trans("messages.email_not_exists"));
        }
        return $response;
    }
}