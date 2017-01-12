<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\WorkExperience;
use App\Models\Schooling;
use App\Helpers\apiResponse;
use Auth;
use App\Models\JobTitles;
use App\Models\JobSeekerSchooling;

class WorkExperienceApiController extends Controller {

    public function __construct() {
        $this->middleware('ApiAuth');
    }
    /**
     * Description : Get joblisting
     * Method : getJobTitlelists
     * formMethod : Get
     * @param 
     * @return type
     */
    public function getJobTitlelists(){
        $job_title = JobTitles::where('is_active',1)->get()->toArray();
        $response = apiResponse::customJsonResponseObject(1, 200, "Jobtitle list",'joblists',$job_title);
        return $response;
    }
    /**
     * Description : To add work experience
     * Method : postWorkExperince
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postWorkExperience(Request $request) {
        try {
            $this->validate($request, [
                'jobTitleId' => 'required|integer',
                'monthsOfExpereince' => 'required|integer',
                'officeName' => 'required',
                'officeAddress' => 'required',
                'city' => 'required',
                'reference1Name'=>'sometimes',
                'reference1Mobile'=>'sometimes',
                'reference1Email' => 'sometimes|email',
                'reference2Name'=>'sometimes',
                'reference2Mobile'=>'sometimes',
                'reference2Email' => 'sometimes|email',
                'action' =>'required|in:add,edit',
                'id'=>'integer|required_if:action,edit'
            ]);
            
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $workExp = new WorkExperience();
            if ($request->action=="edit" && !empty($request->id)) {
                $workExp = WorkExperience::find($request->id);
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
            
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        
        
        
    }

    /**
     * Description : To Delete work experience
     * Method : deleteWorkExperince
     * formMethod : DELETE
     * @param Request $request
     * @return type
     */
    public function deleteWorkExperience(Request $request) {
        try {
            $this->validate($request, [
                'id'=>'required|integer'
            ]);
            
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            
            WorkExperience::where('id', $request->id)->where('user_id',$userId)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            return apiResponse::customJsonResponse(1, 200, trans("messages.work_exp_removed"));
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    /**
     * Description : To list work experience
     * Method : postListWorkExperience
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postListWorkExperience(Request $request)
    {
        try {
            // test
            $start = (int) isset($request->start) ? $request->start : 0;
            $limit = (int) isset($request->limit) ? $request->limit : config('app.defaul_product_per_page');
            
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $query = WorkExperience::getWorkExperienceList($userId, $start, $limit);
            $query['start'] = $start;
            $query['limit'] = $limit;
            
            return apiResponse::customJsonResponse(1, 200, trans("messages.work_exp_list"), apiResponse::convertToCamelCase($query));
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    public function getSchoolList(Request $request)
    {
         try {
            $data = [];
            $jobSeekerData=[];
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $schoolingList = Schooling::getScoolingList();
            $jobseekerSchooling = JobSeekerSchooling::getUserSchoolingList($userId);
            
            if(!empty($jobseekerSchooling)) {
                foreach($jobseekerSchooling as $key=>$value) {
                    $jobSeekerData[$value['schooling_id']] = [ 'schoolingId' => $value['schooling_id'], 'otherSchooling' => $value['other_schooling'],'yearOfGraduation' => $value['year_of_graduation']];
                }
            }
            
            if(!empty($schoolingList)) {
                foreach($schoolingList as $key=>$value) {
                    $data[$value['parentId']]['schoolingId'] = $value['parentId'];
                    $data[$value['parentId']]['schoolName'] = $value['schoolName'];
                    $data[$value['parentId']]['schoolCategory'][] = ['schoolingId' => $value['parentId'], 'schoolingChildId' => $value['childId'],
                                    'schoolChildName' => $value['schoolChildName'], 'jobSeekerStatus' => !empty($jobSeekerData[$value['childId']]) ? 1 : 0,
                                    'otherSchooling' => !empty($jobSeekerData[$value['childId']]) ? $jobSeekerData[$value['childId']]['otherSchooling'] : null,
                                    'yearOfGraduation' => !empty($jobSeekerData[$value['childId']]) ? $jobSeekerData[$value['childId']]['yearOfGraduation'] : null
                                ]; 
                }
            }
            
            $return['list'] = array_values($data);
            
            return apiResponse::customJsonResponse(1, 200, trans("messages.work_exp_list"), apiResponse::convertToCamelCase($return));
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    public function postSchoolSaveUpdate(Request $request) {
        try {
            $this->validate($request, [
                'schoolDataArray' => 'sometimes',
                'other' => 'sometimes',
            ]);
            
            $reqData = $request->all();
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $jobSeekerData = [];
            
            if($userId > 0){
                $deletePreviousSchool = JobSeekerSchooling::where('user_id', '=', $userId)->forceDelete();
                if(!empty($reqData['schoolDataArray']) && is_array($reqData['schoolDataArray'])){
                    foreach($reqData['schoolDataArray'] as $key=>$value){
                        $jobSeekerData[$key] = $value;
                        $jobSeekerData[$key]['userId'] = $userId;
                    }
                }
                
                if(!empty($jobSeekerData))
                {
                    JobSeekerSchooling::insert($jobSeekerData);
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
