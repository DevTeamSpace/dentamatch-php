<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\apiResponse;
use App\Models\Skills;
use App\Models\JobSeekerSkills;
use App\Models\Certifications;
use App\Services\UploadsManager;
use App\Repositories\File\FileRepositoryS3;
use App\Models\JobseekerCertificates;

class SkillApiController extends Controller {
    use FileRepositoryS3;
    public function __construct() {
        
    }
    /**
     * Description : Show skill lists with user skill
     * Method : getSkilllists
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getSkilllists(Request $request){
        $userId = apiResponse::loginUserId($request->header('accessToken'));
        if($userId > 0){
            $skillArray = array();
            $jobseekerSkills  = JobSeekerSkills::where('user_id',$userId)->get();
            if($jobseekerSkills){
                $skillArray = $jobseekerSkills->toArray();
                
                $userSkills = array_map(function ($value) {
                    return  $value['skill_id'];
                }, $skillArray);
            }
            $skill_lists = Skills::where('parent_id',0)->with('children')->get()->toArray();
            $update_skills = array();
            foreach($skill_lists as $key => $skill){
                if($skill['skill_name'] != 'Other'){
                    $subskills = array();
                    if(is_array($skill['children']) && count($skill['children']) > 0){
                        $child_skill = array();
                        foreach($skill['children'] as $subskills){
                            if(in_array($subskills['id'],$userSkills)){
                                $userSkill = 1;
                            }else{
                                $userSkill = 0;
                            }
                            $subSkills = array(
                                'id' => $subskills['id'],
                                'parent_id' => $subskills['parent_id'],
                                'skill_name' => $subskills['skill_name'],
                                'user_skill'=> $userSkill,
                            );
                            if($subskills['skill_name'] == 'Other'){
                                $subSkills['other_skill'] = '';
                                if($userSkill == 1){
                                    $skillKey = array_search($subSkills['skill_id'], array_column($skillArray, 'skill_id'));
                                    if($skillKey && $skillKey >= 0){
                                        $subSkills['other_skill'] = $jobseekerSkills[$skillKey]['other_skill'];
                                    }
                                }
                            }
                            $child_skill[] = $subSkills;
                        }
                    }
                    $update_skills[$key] = array('id' => $skill['id'],'parent_id' => $skill['parent_id'],'skill_name' => $skill['skill_name'],'children' => $child_skill);
                }else{
                     $otherSkill = "";
                     $skillKey = array_search($skill['id'], array_column($skillArray, 'skill_id'));
                        if($skillKey && $skillKey >= 0){
                            $otherSkill = $jobseekerSkills[$skillKey]['other_skill'];
                        }
                    $update_skills[$key] = array('id' => $skill['id'],'parent_id' => $skill['parent_id'],'skill_name' => $skill['skill_name'],'other_skill' => $otherSkill,'children' => array());
                }
                
            }
            
            $response = apiResponse::customJsonResponseObject(1, 200, "Skill list",'list',  apiResponse::convertToCamelCase($update_skills));
            return $response;
        }else{
            return apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
        }
    }
    /**
     * Description : Update user skills
     * Method : postUpdateUserSkills
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postUpdateSkills(Request $request) {
        try {
            $this->validate($request, [
                'skills' => 'sometimes',
                'other' => 'sometimes',
            ]);
            $reqData = $request->all();
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $deletePreviousSkills = JobSeekerSkills::where('user_id', '=', $userId)->forceDelete();
                $jobSeekerSkillModel = new JobSeekerSkills();
                if(is_array($reqData['skills']) && count($reqData['skills']) > 0){
                    foreach($reqData['skills'] as $skill){
                        $jobSeekerSkillModel->user_id = $userId;
                        $jobSeekerSkillModel->skill_id = $skill;
                        $jobSeekerSkillModel->other_skill = '';
                        $jobSeekerSkillModel->save();
                    }
                }
                if(is_array($reqData['other']) && count($reqData['other']) > 0){
                    foreach($reqData['other'] as $otherSkill){
                        $jobSeekerSkillModel->user_id = $userId;
                        $jobSeekerSkillModel->skill_id = $otherSkill['id'];
                        $jobSeekerSkillModel->other_skill = $otherSkill['value'];
                        $jobSeekerSkillModel->save();
                    }
                }
                return apiResponse::customJsonResponse(1, 200, trans("messages.skill_add_success")); 
            }else{
                return apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
            
            
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    /**
     * Description : Get Certification Listing
     * Method : postUpdateUserSkills
     * formMethod : GET
     * @param 
     * @return type
     */
    
    public function getCertificationListing(){
        $certificationList = Certifications::get()->toArray();
        $result = apiResponse::convertToCamelCase($certificationList);
        $response = apiResponse::customJsonResponseObject(1, 200, "Certificate list",'list',$result);
        return $response;
    }
    /**
     * Description : Update certifications
     * Method : postUpdateCertifications
     * formMethod : POST
     * @param 
     * @return type
     */
    public function postUpdateCertifications(Request $request) {
        try {
            $this->validate($request, [
                'certificateId' => 'required|integer',
                'validityDate' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png|max:102400',
            ]);
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $filename = $this->generateFilename($userId, 'certificate');
                $response = $this->uploadFileToAWS($request, $filename);
                if ($response['res']) {
                    $uploadImage  = JobseekerCertificates::updateOrCreate(
                            ['user_id' => $userId, 'certificate_id' => $request->certificateId],
                            ['image_path' => $response['file'] ,'validity_date' => $request->validityDate]
                    );
                    return apiResponse::customJsonResponse(1, 200, trans("message.certificate_successful_update"));
                } else {
                    return apiResponse::responseError(trans("message.upload_image_problem"));
                }
            }else{
                return apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
}