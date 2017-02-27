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
use App\Models\Certifications;
use App\Models\JobTitles;

class UserProfileApiController extends Controller {

    use FileRepositoryS3;

    public function __construct() {
        $this->middleware('ApiAuth');
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
            $userId = $request->userServerData->user_id;
            
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
            $userId = $request->userServerData->user_id;
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
                    apiResponse::chkProfileComplete($userId);
                    /*$img = '/' . $response['file'];
                    $width = 120;
                    $height = 120;
                    $url['img_url'] = url("image/" . $width . "/" . $height . "/?src=" .$img);
                    $url['img_url'] = env('AWS_URL') . '/' . env('AWS_BUCKET') . '/' . $response['file'];*/
                    $url['img_url'] = apiResponse::getThumbImage($response['file']);
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
            ]);
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                UserProfile::where('user_id', $userId)->update(['license_number' => $request->license, 'state' => $request->state]);
                if(($request->jobTitleId != "") && ($request->jobTitleId > 0)){
                    UserProfile::where('user_id', $userId)->update(['job_titile_id' => $request->jobTitleId]);
                }
                apiResponse::chkProfileComplete($userId);
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
            
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                UserProfile::where('user_id', $userId)->update(['about_me' => $request->aboutMe,'is_completed' => 1]);
                apiResponse::chkProfileComplete($userId);
                $response =  apiResponse::customJsonResponse(1, 200, trans("messages.profile_update_success"));
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
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $userProfileModel = UserProfile::where('user_id', $userId)->first();
                $data['list']['aboutMe'] = $userProfileModel->about_me;
                $data['list']['dentalStateBoard']['imageUrl'] = !empty($userProfileModel->dental_state_board) ? apiResponse::getThumbImage($userProfileModel->dental_state_board) : null;
                
                $licenceData = ['license_number' => $userProfileModel->license_number, 'state' => $userProfileModel->state];
                $data['list']['licence'] = $licenceData;
                
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
            $userId = $request->userServerData->user_id;
            $s3Url = env('AWS_URL');
            $s3Bucket = env('AWS_BUCKET');
            $certificationData = [];
            $skillData = [];
            if($userId > 0){
                $userProfileModel = UserProfile::getUserProfile($userId);
                $userWorkExperience = WorkExperience::getWorkExperienceList($userId);
                $schooling = JobSeekerSchooling::getJobSeekerSchooling($userId);
                $otherSchooling = JobSeekerSchooling::getJobseekerOtherSchooling($userId);
                $schooling = array_merge($schooling, $otherSchooling);
                $skills = JobSeekerSkills::getJobSeekerSkills($userId);
                $otherSkills = JobSeekerSkills::getJobseekerOtherSkills($userId);
                $skills = array_merge($skills, $otherSkills);
                if(!empty($skills)) {
                    foreach($skills as $keySkill=>$skillValue) {
                        $skillData[$skillValue['parentId']]['id'] = $skillValue['parentId'];
                        $skillData[$skillValue['parentId']]['skillName'] = $skillValue['skillsName'];
                        $skillData[$skillValue['parentId']]['children'][] = [
                                                                        'id' => $skillValue['childId'],
                                                                        'skillName' => $skillValue['skillsChildName'],
                                                                        'otherSkill' => $skillValue['otherSkills']
                                                                    ];
                    }
                }
                $jobTitle = JobTitles::where('is_active',1)->get()->toArray();
                $affiliations = JobSeekerAffiliation::getJobSeekerAffiliation($userId);
                $jobSeekerCertifications = JobseekerCertificates::getJobSeekerCertificates($userId);
                $allCertification = Certifications::getAllCertificates();
                if(!empty($allCertification)) {
                    foreach($allCertification as $key=>$value){
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
    
    /**
     * Description : Update Job Seeker Profile
     * Method : updateUserProfile
     * formMethod : PUT
     * @param Request $request
     * @return type
     */
    public function updateUserProfile(Request $request) {
        try {
            $this->validate($request, [
                'firstName' => 'required',
                'lastName' => 'required',
                'zipcode' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'preferredJobLocation'=>'required',
                'jobTitileId'=>'required|integer',
                'aboutMe' => 'required'
            ]);
            $reqData = $request->all();
            $userId = $request->userServerData->user_id;
            if($userId>0) {
                $userProfile = UserProfile::where('user_id', $userId)->first();
                $userProfile->first_name = $reqData['firstName'];
                $userProfile->last_name = $reqData['lastName'];
                $userProfile->zipcode = $reqData['zipcode'];
                $userProfile->latitude = $reqData['latitude'];
                $userProfile->longitude = $reqData['longitude'];
                $userProfile->preferred_job_location = $reqData['preferredJobLocation'];
                $userProfile->job_titile_id = $reqData['jobTitileId'];
                $userProfile->about_me = $reqData['aboutMe'];
                $userProfile->save();
                apiResponse::chkProfileComplete($userId);
                $message = trans("messages.user_profile_updated");
                $returnResponse = apiResponse::customJsonResponse(1, 200, $message);
            } else {
                $returnResponse = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
            
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $returnResponse = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $returnResponse = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        
        return $returnResponse;
    }
    /**
     * Description : Update Job Seeker location
     * Method : updateUserLocationUpdate
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function updateUserLocationUpdate(Request $request){
        try {
            $this->validate($request, [
                'preferedLocation' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'zipCode' => 'required',
            ]);
            $reqData = $request->all();
            $userId = $request->userServerData->user_id;
            if($userId>0) {
                $userProfile = UserProfile::where('user_id', $userId)->first();
                $userProfile->zipcode = $reqData['zipCode'];
                $userProfile->latitude = $reqData['latitude'];
                $userProfile->longitude = $reqData['longitude'];
                $userProfile->preferred_job_location = $reqData['preferedLocation'];
                $userProfile->save();
                $returnResponse = apiResponse::customJsonResponse(1, 200, trans("messages.location_update_success"));
            } else {
                $returnResponse = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
            return $returnResponse;
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    
}
