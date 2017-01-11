<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use DB;
use App\Models\JobTitles;
use App\Helpers\apiResponse;
use App\Models\Skills;

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
                        $child_skill[] = array(
                            'id' => $subskills['id'],
                            'parent_id' => $subskills['parent_id'],
                            'skill_name' => $subskills['skill_name'],
                            'user_skill'=> 0,
                        );
                    }
                }
                $update_skills[$key] = array('id' => $skill['id'],'parent_id' => $skill['parent_id'],'skill_name' => $skill['skill_name'],'children' => $child_skill);
            }
            
            $response = apiResponse::customJsonResponseObject(1, 200, "Skill list",'skillList',  apiResponse::convertToCamelCase($update_skills));
            return $response;
        }else{
            return apiResponse::customJsonResponse(0, 204, "invalid user token");
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
                'skills' => 'required',
                'other' => 'required',
            ]);
            $reqData = $request->all();
            
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
               
            if (isset($request->id) && !empty($request->id)) {
                
            }
            
            $workExp->user_id = $userId;
            $workExp->job_title_id = $request->jobTitleId;
            $workExp->months_of_expereince = $request->monthsOfExpereince;
            $workExp->office_name = $request->officeName;
            $workExp->office_address = $request->officeAddress;
            $workExp->city = $request->city;
            $workExp->reference1_name = $request->reference1Name;
            $workExp->reference1_mobile = $request->reference1Mobile;
            $workExp->reference1_email = $request->reference1Email;
            $workExp->reference2_name = $request->reference2Name;
            $workExp->reference2_mobile = $request->reference2Mobile;
            $workExp->reference2_email = $request->reference2Email;
            $workExp->deleted_at = null;
            $workExp->save();
            
            $data['list'] = $workExp->toArray();
            return apiResponse::customJsonResponse(1, 200, trans("messages.work_exp_added"), $data); 
            }else{
                return apiResponse::customJsonResponse(0, 204, "invalid user token"); 
            }
            
            
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        
        
        
    }
    
}