<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use App\Models\JobTitles;
use App\Helpers\apiResponse;
use App\Models\Skills;
use App\Models\JobSeekerSkills;

class SkillApiController extends Controller {
    
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
        $userId = 10;
        if($userId > 0){
            $skill_lists = Skills::where('parent_id',0)->with('children')->get()->toArray();
            $update_skills = array();
            foreach($skill_lists as $key => $skill){
                $subskills = array();
                if(is_array($skill['children']) && count($skill['children']) > 0){
                    $child_skill = array();
                    foreach($skill['children'] as $subskills){
                        $skill_exists = JobSeekerSkills::where('user_id',$userId)->where('skill_id',$subskills['id'])->get()->toArray();
                        if($skill_exists){
                            $userSkill = 1;
                        }else{
                            $userSkill = 0;
                        }
                        $child_skill[] = array(
                            'id' => $subskills['id'],
                            'parent_id' => $subskills['parent_id'],
                            'skill_name' => $subskills['skill_name'],
                            'user_skill'=> $userSkill,
                        );
                    }
                }
                $update_skills[$key] = array('id' => $skill['id'],'parent_id' => $skill['parent_id'],'skill_name' => $skill['skill_name'],'children' => $child_skill);
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
    
}