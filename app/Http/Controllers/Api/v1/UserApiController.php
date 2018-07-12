<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\SearchFilter;
use App\Models\PasswordReset;
use App\Models\ChatUserLists;
use App\Models\JobSeekerTempAvailability;
use Mail;
use Auth;
use App\Helpers\ApiResponse;
class UserApiController extends Controller {
    
    public function __construct() {
        
    }
    
    /**
     * Description : Signup User
     * Method : postSignup
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postSignup(Request $request){
        try {
            
            $this->validate($request, [
                'deviceId' => 'required',
                'deviceType' => 'required',
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required',
                'password' => 'required',
                'preferredJobLocationId' => 'required',
            ]);
        
        $reqData = $request->all();
        $mappedSkillsArray = [];
        
        $userExists = User::with('userGroup')->where('email', $reqData['email'])->first();
        if($userExists){
            if (isset($userExists->userGroup) && !empty($userExists->userGroup)) {
                    if ($userExists->userGroup->group_id == UserGroup::RECRUITER) {
                        $msg = trans("messages.already_register_as_recruiter");
                    } else {
                        $msg = trans("messages.user_exist_same_email");
                    }
                }
            $response = ApiResponse::customJsonResponse(0, 201,$msg);      
        }else{
            $uniqueCode = uniqid();
            $user =  array(
                'email' => $reqData['email'],
                'password' => bcrypt($reqData['password']),
                'verification_code' => $uniqueCode,
            );
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
            
            $deviceModel =  new Device();
            $reqData['deviceOs'] = isset($reqData['deviceOs'])?$reqData['deviceOs']:'';
            $reqData['appVersion'] = isset($reqData['appVersion'])?$reqData['appVersion']:'';
            $deviceToken = isset($reqData['deviceToken']) ? $reqData['deviceToken'] : "";
            $deviceModel->registerDevice(
                    $reqData['deviceId'], $userDetails->id, $deviceToken,
                    $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']
                );
            
            $current = strtotime(date('Y-m-d'));
            $last = strtotime(date('Y-m-d')." +60 days");
            JobSeekerTempAvailability::addTempDateAvailability($userDetails->id, $current, $last);
            
            $url = url("/verification-code/$uniqueCode");
            $name = $reqData['firstName'];
            $email = $reqData['email'];
            $fname = $reqData['firstName'];
            Mail::queue('email.user-activation', ['name' => $name, 'url' => $url, 'email' => $reqData['email']], function($message ) use($email,$fname) {
                    $message->to($email, $fname)->subject('Activation Email');
                });
            $userData['userDetails'] = User::getUser($userDetails->id);
            $response = ApiResponse::customJsonResponse(1, 200, trans("messages.user_registration_successful"), ApiResponse::convertToCamelCase($userData)); 
        }
        return $response;
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }catch (\Exception $e) {
            return ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    /**
     *  Method to find log-in the user via POST method
     * @param Request $request
     * @return type
     */
    public function postSignIn(Request $request) {
        try {
            $this->validate($request, [
                'deviceId' => 'required',
                'deviceType' => 'required',
                'email' => 'required|email',
                'password' => 'required', 
            ]);
        
        $reqData = $request->all();
        $userAttempt = Auth::attempt(['email' => $reqData['email'], 'password' => $reqData['password']]);
        $userId = ($userAttempt==true) ? Auth::user()->id : null;
        if($userId > 0){
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->leftjoin('job_titles','jobseeker_profiles.job_titile_id' , '=','job_titles.id')
                        ->leftjoin('preferred_job_locations','preferred_job_locations.id' , '=','jobseeker_profiles.preferred_job_location_id')
                        ->select(
                                'user_groups.group_id', 
                                'users.email','users.id','users.is_active',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'jobseeker_profiles.profile_pic',
                                'jobseeker_profiles.zipcode','jobseeker_profiles.is_job_seeker_verified',
                                'jobseeker_profiles.preferred_job_location', 'jobseeker_profiles.state',
                                'jobseeker_profiles.latitude', 'jobseeker_profiles.license_number',
                                'jobseeker_profiles.longitude','jobseeker_profiles.preferred_city',
                                'jobseeker_profiles.preferred_state','jobseeker_profiles.preferred_country',
                                'jobseeker_profiles.about_me','jobseeker_profiles.preferred_job_location_id',
                                'users.is_verified','jobseeker_profiles.is_completed', 'jobseeker_profiles.job_titile_id',
                                'preferred_job_locations.preferred_location_name', 'job_titles.jobtitle_name'
                                )
                        ->where('users.id', $userId)
                        ->first();
            if($userData['group_id'] == UserGroup::JOBSEEKER){
                if($userData['is_verified'] == UserProfile::JOBSEEKER_VERIFY_APPROVED && $userData['is_active'] == 1){
                        $device = Device::where('user_id', $userId)->orWhere('device_id', $reqData['deviceId'])->first();
                        $reqData['deviceOs'] = isset($reqData['deviceOs'])?$reqData['deviceOs']:'';
                        $reqData['appVersion'] = isset($reqData['appVersion'])?$reqData['appVersion']:'';
                         if (is_object($device) && ($device->device_id != $reqData['deviceId'] || $device->user_id != $userId)) {
                            Device::where('device_id', $device->device_id)->orWhere('user_id', $userId)->delete();
                            $deviceModel = new Device();
                            $userToken = $deviceModel->registerDevice($reqData['deviceId'], $userId, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                        } else {
                            $deviceModel = new Device();
                            $userToken = $deviceModel->registerDevice($reqData['deviceId'], $userId, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                        }
                        $imgUrl = "";
                        if($userData['profile_pic']){
                            $imgUrl = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $userData['profile_pic'];
                        }
                        $userArray['userDetails'] = [
                            'id' => $userData['id'],
                            'email' => $userData['email'],
                            'firstName' => $userData['first_name'],
                            'lastName' => $userData['last_name'],
                            'imageUrl' => $imgUrl,
                            'zipCode' => $userData['zipcode'],
                            'preferredJobLocation' => $userData['preferred_job_location'],
                            'preferredJobLocationId' => $userData['preferred_job_location_id'],
                            'latitude' => $userData['latitude'],
                            'longitude' => $userData['longitude'],
                            'accessToken' => $userToken,
                            'profileCompleted' => $userData['is_completed'],
                            'city' => $userData['preferred_city'],
                            'state' => $userData['state'],
                            'country' => $userData['preferred_country'],
                            'aboutMe' => $userData['about_me'],
                            'licenseNumber' => $userData['license_number'],
                            'preferredLocationName' => $userData['preferred_location_name'],
                            'jobtitleName' => $userData['jobtitle_name'],
                            'jobTitileId' => $userData['job_titile_id'],
                            'isVerified' => $userData['is_verified'],
                            'isJobSeekerVerified' => $userData['is_job_seeker_verified']
                        ];
                        $searchArray = SearchFilter::getFiltersOnLogin($userId);
                        if($searchArray){
                        $userArray['searchFilters'] = ApiResponse::convertToCamelCase($searchArray);
                        }else{
                           $userArray['searchFilters'] = $searchArray; 
                        }
                        $response = ApiResponse::customJsonResponse(1, 200, trans("messages.user_logged_successful"),$userArray);
                }else{
                    $response = ApiResponse::customJsonResponse(0, 202, trans("messages.user_account_not_active")); 
                }
            }else{
                $response = ApiResponse::customJsonResponse(0, 201, trans("messages.invalid_login_credentials")); 
            }
        }else{
            $response = ApiResponse::customJsonResponse(0, 201,trans("messages.invalid_login_credentials")); 
        }
           return $response;
         } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }catch (\Exception $e) {
            return ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    /**
     * Description : Get Terms and Conditions
     * Method : getTermsAndCondition
     * formMethod : GET
     */
    public function getTermsAndCondition() {
        return view('terms-and-condition');   
    }
    
    /**
     * Description : Get Privacy Policy
     * Method : getPrivacypolicy
     * formMethod : GET
     */
    public function getPrivacypolicy() {
        return view('privacy-policy');   
    }

    /**
     * Description : Get Activate Jobseeker
     * Method : getActivatejobseeker
     * formMethod : GET
     */
    public  function getActivatejobseeker($confirmation_code) { 
        $is_verified = 0;
        $profile_details = UserProfile::where('verification_code', $confirmation_code)->first();
        if(is_object($profile_details) && $profile_details->is_verified == UserProfile::JOBSEEKER_VERIFY_DEFAULT){
                $update_profile = UserProfile::find($profile_details->id);
                $update_profile->is_verified = UserProfile::JOBSEEKER_VERIFY_APPROVED;
                $update_profile->save();
                $is_verified = 1;
        }
        return view('verify-user')->with('verifyUser', $is_verified);
    }
    
    /**
     * Method to make forgot password  request
     * @param Request $request
     * @return type
     */
    public function putForgotPassword(Request $request) {
        try {
            $this->validate($request, [
                'email' => 'required|email',
            ]);
            $reqData = $request->all();
            $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.id',
                                'user_groups.group_id', 
                                'users.email',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'users.is_verified'
                                )
                        ->where('users.email', $reqData['email'])
                        ->where('user_groups.group_id' , 3)
                        ->first();
            if ($user) {
                if($user->is_verified == UserProfile::JOBSEEKER_VERIFY_APPROVED){
                    PasswordReset::where('user_id' , $user->id)->where('email', $user->email)->delete();
                    $token = \Illuminate\Support\Facades\Crypt::encrypt($user->email . time());
                    $passwordModel = PasswordReset::firstOrNew(array('user_id' => $user->id, 'email' => $user->email));
                    $passwordModel->fill(['token' => $token]);
                    $passwordModel->save();
                
                    Mail::queue('email.reset-password-token', ['name' => $user->first_name, 'url' => url('password/reset', ['token' => $token]), 'email' => $user->email], function($message) use ($user) {
                        $message->to($user->email, $user->first_name)->subject('Reset Password Request ');
                    });
                    $response = ApiResponse::customJsonResponse(1, 200, trans("messages.reset_pw_email_sent"));
                }else{
                    $response = ApiResponse::customJsonResponse(0, 202, trans("messages.user_account_not_active")); 
                }
                
            }else{
                $response = ApiResponse::customJsonResponse(0, 201, trans("messages.email_not_exists"));
            }
            return $response;
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return ApiResponse::responseError("Some error occoured", ["data" => $message]);
        }
        
    }
    
    /**
     * Method to logout user from the system
     * @param Request $request
     * @return type
     */
    public function deleteSignOut(Request $request) {
        
        try {
            
            $userId = ApiResponse::loginUserId($request->header('accessToken'));
            if($userId>0) {
                Device::unRegisterAll($userId);
                $returnResponse = ApiResponse::customJsonResponse(1, 200, trans("messages.user_signout"));
            } else {
                $returnResponse = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $returnResponse = ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            $returnResponse = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $returnResponse;
    }
    
    public function chatRecruiterList(Request $request){
        try {
            
            $userId = ApiResponse::loginUserId($request->header('accessToken'));
            if($userId>0) {
                $recruiterList = ChatUserLists::getRecruiterListForChat($userId);
                $returnResponse = ApiResponse::customJsonResponse(1, 200, '',['list'=>$recruiterList]);
            } else {
                $returnResponse = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $returnResponse = ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            $returnResponse = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $returnResponse;
    }
    
    public function chatBlockUnblockRecruiter(Request $request){
        try {
            $this->validate($request, [
                'recruiterId' => 'required',
                'blockStatus' => 'required|in:0,1,',
            ]);
            $userId = ApiResponse::loginUserId($request->header('accessToken'));
            if($userId>0) {
                $blockStatus = ChatUserLists::blockUnblockSeekerOrRecruiter($userId, $request->recruiterId, $request->blockStatus);
                $returnResponse = ApiResponse::customJsonResponse(1, 200, trans("messages.recruiter_blocked"),['recruiterId'=>$request->recruiterId,'blockStatus'=>$blockStatus]);
            } else {
                $returnResponse = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $returnResponse = ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            $returnResponse = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $returnResponse;
    }
    
    public function postAdminForgotPassword(Request $request) {
        try {
            $this->validate($request, [
                'email' => 'required|email',
            ]);
            $reqData = $request->all();
            $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                            ->select(
                                'users.id',
                                'user_groups.group_id', 
                                'users.email',
                                'users.is_verified'
                                )
                        ->where('users.email', $reqData['email'])
                        ->where('user_groups.group_id' , UserGroup::ADMIN)
                        ->first();
            
            if ($user) {
                
                    PasswordReset::where('user_id' , $user->id)->where('email', $user->email)->delete();
                    $token = \Illuminate\Support\Facades\Crypt::encrypt($user->email . time());
                    $passwordModel = PasswordReset::firstOrNew(array('user_id' => $user->id, 'email' => $user->email));
                    $passwordModel->fill(['token' => $token]);
                    $passwordModel->save();
                
                    Mail::queue('email.reset-password-token', ['name' => 'Admin', 'url' => url('password/reset', ['token' => $token]), 'email' => $user->email], function($message) use ($user) {
                        $message->to($user->email, $user->first_name)->subject('Reset Password Request ');
                    });
                    $response = ApiResponse::customJsonResponse(1, 200, trans("messages.reset_pw_email_sent"));
                }else{
                $response = ApiResponse::customJsonResponse(0, 201, trans("messages.email_not_exists"));
            }
            return $response;
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return ApiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return ApiResponse::responseError("Some error occoured", ["data" => $message]);
        }
        
    }
}