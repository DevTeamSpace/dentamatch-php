<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\PasswordReset;
use Mail;
use Auth;
use App\Helpers\apiResponse;
class UserApiController extends Controller {
    
    public function __construct() {
        
    }
    public function postSignup(Request $request){
        try {
            $this->validate($request, [
                'deviceId' => 'required',
                'deviceType' => 'required',
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required',
                'password' => 'required',
                'preferedLocation' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'zipCode' => 'required',
            ]);
        
        $reqData = $request->all();
        $userExists = User::where('email', $reqData['email'])->first();
        if($userExists){
            $response = apiResponse::customJsonResponse(0, 201, trans("messages.user_exist_same_email"));      
        }else{
            $uniqueCode = uniqid();
            $user =  array(
                'email' => $reqData['email'],
                'password' => bcrypt($reqData['password']),
                'verification_code' => $uniqueCode,
            );
            $userDetails = User::create($user);
            $userGroupModel = new UserGroup();
            $userGroupModel->group_id = 3;
            $userGroupModel->user_id = $userDetails->id;
            $userGroupModel->save();
            
            $userProfileModel = new UserProfile();
            
            $userProfileModel->user_id = $userDetails->id;
            $userProfileModel->first_name = $reqData['firstName'];
            $userProfileModel->last_name = $reqData['lastName'];
            $userProfileModel->zipcode = $reqData['zipCode'];
            $userProfileModel->preferred_job_location = $reqData['preferedLocation'];
            $userProfileModel->latitude = $reqData['latitude'];
            $userProfileModel->longitude = $reqData['longitude'];
            
            $userProfileModel->save();
            
            $deviceModel =  new Device();
            $reqData['deviceOs'] = isset($reqData['deviceOs'])?$reqData['deviceOs']:'';
            $reqData['appVersion'] = isset($reqData['appVersion'])?$reqData['appVersion']:'';
            $deviceModel->register_device(
                    $reqData['deviceId'], $userDetails->id, $reqData['deviceToken'],
                    $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']
                );
            
            $url = url("/verification-code/$uniqueCode");
            $name = $reqData['firstName'];
            $email = $reqData['email'];
            $fname = $reqData['firstName'];
            Mail::queue('email.userActivation', ['name' => $name, 'url' => $url, 'email' => $reqData['email']], function($message ) use($email,$fname) {
                    $message->to($email, $fname)->subject('Activation Email');
                });
            
            $response = apiResponse::customJsonResponse(1, 200, trans("messages.user_registration_successful")); 
        }
        return $response;
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
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
                        ->select(
                                'user_groups.group_id', 
                                'users.email',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'jobseeker_profiles.zipcode',
                                'jobseeker_profiles.preferred_job_location',
                                'users.is_verified'
                                )
                        ->where('users.id', $userId)
                        ->first();
            if($userData['group_id'] == 3){
                if($userData['is_verified'] == 1){
                        $device = Device::where('user_id', $userId)->orWhere('device_id', $reqData['deviceId'])->first();
                        $reqData['deviceOs'] = isset($reqData['deviceOs'])?$reqData['deviceOs']:'';
                        $reqData['appVersion'] = isset($reqData['appVersion'])?$reqData['appVersion']:'';
                         if (is_object($device) && ($device->device_id != $reqData['deviceId'] || $device->user_id != $userId)) {
                            Device::where('device_id', $device->device_id)->orWhere('user_id', $userId)->delete();
                            $deviceModel = new Device();
                            $userToken = $deviceModel->register_device($reqData['deviceId'], $userId, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                        } else {
                            $deviceModel = new Device();
                            $userToken = $deviceModel->register_device($reqData['deviceId'], $userId, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                        }
                        $userArray['userDetails'] = array(
                            'email' => $userData['email'],
                            'firstName' => $userData['first_name'],
                            'lastName' => $userData['last_name'],
                            'zipCode' => $userData['zipcode'],
                            'preferredJobLocation' => $userData['preferred_job_location'],
                            'accessToken' => $userToken,
                        );
                        $response = apiResponse::customJsonResponse(1, 200, trans("messages.user_logged_successful"),$userArray);
                }else{
                    $response = apiResponse::customJsonResponse(0, 202, trans("messages.user_account_not_active")); 
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 201, trans("messages.invalid_login_credentials")); 
            }
        }else{
            $response = apiResponse::customJsonResponse(0, 201,trans("messages.invalid_login_credentials")); 
        }
           return $response;
         } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    public function getTermsAndCondition() {
        return view('terms-and-condition');   
    }
    
    public function getPrivacypolicy() {
        return view('privacy-policy');   
    }

    public  function getActivatejobseeker($confirmation_code) { 
        $is_verified = 0;
        $profile_details = UserProfile::where('verification_code', $confirmation_code)->first();
        if($profile_details){
            if($profile_details->is_verified == 0){
                $update_profile = UserProfile::find($profile_details->id);
                $update_profile->is_verified = 1;
                $update_profile->save();
                $is_verified = 1;
            }else{
                $is_verified = 0;
            }
        }else{
            $is_verified = 0;
        }
        return view('verifyUser')->with('verifyUser', $is_verified);
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
                                'jobseeker_profiles.is_verified'
                                )
                        ->where('users.email', $reqData['email'])
                        ->where('user_groups.group_id' , 3)
                        ->first();
            if ($user) {
                if($user->is_verified == 1){
                    PasswordReset::where('user_id' , $user->id)->where('email', $user->email)->delete();
                    $passwordModel = PasswordReset::firstOrNew(array('user_id' => $user->id, 'email' => $user->email));
                    $passwordModel->fill(['token' =>md5($user->email . time())]);
                    $passwordModel->save();
                
                    Mail::queue('email.resetPasswordToken', ['name' => $user->first_name, 'url' => url('resetPassword', ['token' => md5($user->email . time())]), 'email' => $user->email], function($message) use ($user) {
                        $message->to($user->email, $user->first_name)->subject('Reset Password Request ');
                    });
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.reset_pw_email_sent"));
                }else{
                    $response = apiResponse::customJsonResponse(0, 202, trans("messages.user_account_not_active")); 
                }
                
            }else{
                $response = apiResponse::customJsonResponse(0, 201, trans("messages.email_not_exists"));
            }
            return $response;
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return apiResponse::responseError("Some error occoured", ["data" => $message]);
        }
        
    }
}