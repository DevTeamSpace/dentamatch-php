<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use Hash;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Device;
use App\Models\UserProfile;
use App\Models\PasswordReset;
use App\Models\JobTitles;
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
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
        $reqData = $request->all();
        $userExists = User::where('email', $reqData['email'])->first();
        if($userExists){
            $response = apiResponse::customJsonResponse(0, 201, "User already exists with this email");      
        }else{
            $user =  array(
                'email' => $reqData['email'],
                'password' => bcrypt($reqData['password']),
            );
            $user_details = User::create($user);
            
            $userGroupModel = new UserGroup();
            $userGroupModel->group_id = 3;
            $userGroupModel->user_id = $user_details->id;
            $userGroupModel->save();
            
            $userProfileModel = new UserProfile();
            $verification_code = mt_rand(1000000000, 9999999999);
            $userProfileModel->user_id = $user_details->id;
            $userProfileModel->first_name = $reqData['firstName'];
            $userProfileModel->last_name = $reqData['lastName'];
            $userProfileModel->zipcode = $reqData['zipCode'];
            $userProfileModel->preferred_job_location = $reqData['preferedLocation'];
            $userProfileModel->latitude = $reqData['latitude'];
            $userProfileModel->longitude = $reqData['longitude'];
            $userProfileModel->verification_code = $verification_code;
            $userProfileModel->save();
            
            $deviceModel =  new Device();
            $reqData['deviceOs'] = isset($reqData['deviceOs'])?$reqData['deviceOs']:'';
            $reqData['appVersion'] = isset($reqData['appVersion'])?$reqData['appVersion']:'';
            $user_token=$deviceModel->register_device(
                    $reqData['deviceId'],
                    $user_details->id,
                    $reqData['deviceToken'],
                    $reqData['deviceType'],
                    $reqData['deviceOs'],
                    $reqData['appVersion']);
            
            $url = url('user-activation', ['token' => $verification_code]);
            $name = $reqData['firstName'];
            $email = $reqData['email'];
            $fname = $reqData['firstName'];
            Mail::queue('email.userActivation', ['name' => $name, 'url' => $url, 'email' => $reqData['email']], function($message ) use($email,$fname) {
                    $message->to($email, $fname)->subject('Activation Email');
                });
            
            $response = apiResponse::customJsonResponse(1, 200, "User registered successfully"); 
        }
        return $response;
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
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
        $reqData = $request->all();
        $userAttempt = Auth::attempt(['email' => $reqData['email'], 'password' => $reqData['password']]);
        $user_id = ($userAttempt==true) ? Auth::user()->id : null;
        if($user_id > 0){
            $user_data = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'user_groups.group_id', 
                                'users.email',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'jobseeker_profiles.zipcode',
                                'jobseeker_profiles.preferred_job_location',
                                'jobseeker_profiles.is_verified'
                                )
                        ->where('users.id', $user_id)
                        ->first();
            if($user_data['group_id'] == 3){
                if($user_data['is_verified'] == 1){
                        $device = Device::where('user_id', $user_id)->orWhere('device_id', $reqData['deviceId'])->first();
                        $reqData['deviceOs'] = isset($reqData['deviceOs'])?$reqData['deviceOs']:'';
                        $reqData['appVersion'] = isset($reqData['appVersion'])?$reqData['appVersion']:'';
                         if (is_object($device) && ($device->device_id != $reqData['deviceId'] || $device->user_id != $user_id)) {
                            Device::where('device_id', $device->device_id)->orWhere('user_id', $user_id)->delete();
                            $deviceModel = new Device();
                            $user_token = $deviceModel->register_device($reqData['deviceId'], $user_id, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                        } else {
                            $deviceModel = new Device();
                            $user_token = $deviceModel->register_device($reqData['deviceId'], $user_id, $reqData['deviceToken'], $reqData['deviceType'], $reqData['deviceOs'], $reqData['appVersion']);

                        }
                        $user_array['userDetails'] = array(
                            'email' => $user_data['email'],
                            'firstName' => $user_data['first_name'],
                            'lastName' => $user_data['last_name'],
                            'zipCode' => $user_data['zipcode'],
                            'preferredJobLocation' => $user_data['preferred_job_location'],
                            'accessToken' => $user_token,
                        );
                        $response = apiResponse::customJsonResponse(1, 200, "User loggedin successfully",$user_array);
                }else{
                    $response = apiResponse::customJsonResponse(0, 202, "Your account is not activated yet"); 
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 201, "Invalid login credentials"); 
            }
        }else{
            $response = apiResponse::customJsonResponse(0, 201, "Invalid login credentials"); 
        }
        return $response;
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
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
        try {
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
                    $delete = PasswordReset::where('user_id' , $user->id)
                                    ->where('email', $user->email)
                                    ->delete();
                    $passwordModel = PasswordReset::firstOrNew(array('user_id' => $user->id, 'email' => $user->email));
                    $passwordModel->fill(['token' =>md5($user->email . time())]);
                    $passwordModel->save();
                
                    Mail::queue('email.resetPasswordToken', ['name' => $user->first_name, 'url' => url('resetPassword', ['token' => md5($user->email . time())]), 'email' => $user->email], function($message) use ($user) {
                        $message->to($user->email, $user->first_name)->subject('Reset Password Request ');
                    });
                    $response = apiResponse::customJsonResponse(1, 200, "Please check your mailbox");
                }else{
                    $response = apiResponse::customJsonResponse(0, 202, "Your account is not activated yet"); 
                }
                
            }else{
                $response = apiResponse::customJsonResponse(0, 201, "Email does not exists");
            }
            return $response;
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            return apiResponse::responseError("Some error occoured", ["data" => $message]);
        }
        
    }
    
    
    
}