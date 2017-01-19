<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Helpers\apiResponse;
use App\Repositories\File\FileRepositoryS3;
use App\Models\WorkExperience;
use App\Models\JobSeekerSchooling;
use App\Models\JobSeekerSkills;
use App\Models\JobSeekerAffiliation;
use App\Models\JobseekerCertificates;

class UserProfileApiController extends Controller {

    use FileRepositoryS3;

    public function __construct() {
    }
    
    /**
     * Method for change password
     * @param Request $request
     * @return type
     */
    public function postChangePassword(Request $request) {
        try {
            $this->validate($request, [
                'oldPassword' => 'required|max:255',
                'newPassword' => 'required|min:6|max:255',
                'confirmNewPassword' => 'required|min:6|max:255'
            ]);
            $reqData = $request->all();
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            
            if(isset($userId) && $userId > 0){
                $userModel = User::where('id', $userId)->first();
                if(!Hash::check($reqData['oldPassword'], $userModel->password)) {
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.incorrect_old_password")); 
                }else if ($reqData['newPassword'] !== $reqData['confirmNewPassword']) {
                    $response = apiResponse::customJsonResponse(0, 202, trans("messages.mismatch_pw_cpw")); 
                } else if (!empty($userModel)) {
                    $userModel->password = bcrypt($reqData['newPassword']);
                    $userModel->save();
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.password_update_successful")); 
                } else {
                    $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
            
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            $response =  apiResponse::responseError("Some error occoured", ["data" => $message]);
        }
        return $response;
    }
    
    /**
     * Upload User Image
     * @param Request $request
     * @return type
     */
    public function postUploadImage(Request $request) {
        try {
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $filename = $this->generateFilename($request->type);
                $response = $this->uploadFileToAWS($request, $filename);
                if ($response['res']) {
                    //$file = str_replace($request->type . '/', '', $response['file']);
                    if ($request->type == 'profile_pic') {
                        UserProfile::where('user_id', $userId)->update(['profile_pic' => $response['file']]);
                    } else {
                        UserProfile::where('user_id', $userId)->update(['dental_state_board' => $response['file']]);
                    }
                    $url['img_url'] = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $response['file'];
                    $response =  apiResponse::customJsonResponse(1, 200, trans("messages.image_upload_success"), $url);
                } else {
                    $response =  apiResponse::responseError(trans("messages.prob_upload_image"));
                }
            }else{
                $response =  apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response =  apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }catch (\Exception $ex) {
            $message = $ex->getMessage();
            $response =  apiResponse::responseError("Some error occoured", ["data" => $message]);
        }
        return $response;
    }

    /**
     * Method to update license
     * @param Request $request
     * @return type
     */
    public function putUpdateLicense(Request $request) {
        try {
            $this->validate($request, [
                'license' => 'required',
                'state' => 'required',
                'jobTitleId' => 'required'
            ]);
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                UserProfile::where('user_id', $userId)->update(['license_number' => $request->license, 'state' => $request->state]);
                $response =  apiResponse::customJsonResponse(1, 200, trans("messages.data_saved_success"));
            }else{
                $response =  apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
         } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response =  apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response =  apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
        return $response;
    }
    
    /**
     * Method to update or insert About Me detail of a user
     * @param Request $request
     * @return type
     */
    public function postAboutMe(Request $request) {
        try {
            $this->validate($request, [
                'aboutMe' => 'required',
            ]);
            
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                UserProfile::where('user_id', $userId)->update(['about_me' => $request->aboutMe]);
                $response =  apiResponse::customJsonResponse(1, 200, "About Me Updated Successfully");
            }else{
                $response =  apiResponse::customJsonResponse(0, 204, "invalid user token");
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response =  apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            $response =  apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    /**
     * Method to fetch About Me data of a user
     * @param Request $request
     * @return type
     */
    public function getAboutMe(Request $request) {
        try {
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $userProfileModel = UserProfile::where('user_id', $userId)->first();
                $data['list']['aboutMe'] = $userProfileModel->about_me;
                
                $response =  apiResponse::customJsonResponse(1, 200, trans("messages.about_me_list"), apiResponse::convertToCamelCase($data));
            }else{
                $response =  apiResponse::customJsonResponse(0, 204, "invalid user token");
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response =  apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            $response =  apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    /**
     * Method to fetch user profile
     * @param Request $request
     * @return type
     */
    public function getUserProfile(Request $request) {
        try {
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $s3Url = env('AWS_URL');
            $s3Bucket = env('AWS_BUCKET');
            
            if($userId > 0){
                $userProfileModel = UserProfile::getUserProfile($userId);
                $userWorkExperience = WorkExperience::getWorkExperienceList($userId);
                $schooling = JobSeekerSchooling::getJobSeekerSchooling($userId);
                $skills = JobSeekerSkills::getJobSeekerSkills($userId);
                $affiliations = JobSeekerAffiliation::getJobSeekerAffiliation($userId);
                $certifications = JobseekerCertificates::getJobSeekerCertificates($userId);
                
                $data['user'] = $userProfileModel;
                $data['dentalStateBoard']['imageUrl'] = $userProfileModel['dental_state_board'];
                
                $licenceData = ['license_number' => $userProfileModel['license_number'], 'state' => $userProfileModel['state']];
                $data['licence'] = $licenceData;
                
                $data['school'] = $schooling;
                $data['skills'] = $skills;
                $data['affiliations'] = $affiliations;
                $data['certifications'] = $certifications;
                $data['workExperience'] = $userWorkExperience;
                
                $response =  apiResponse::customJsonResponse(1, 200, trans("messages.user_profile_list"), apiResponse::convertToCamelCase($data));
            }else{
                $response =  apiResponse::customJsonResponse(0, 204, "invalid user token");
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response =  apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            $response =  apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
}
