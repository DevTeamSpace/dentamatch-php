<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\WorkExperience;
use App\Helpers\apiResponse;
use Auth;

class WorkExperienceApiController extends Controller {

    public function __construct() {
        $this->middleware('ApiAuth');
    }

    /**
     * Description : To add work experience
     * Method : postWorkExperince
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postWorkExperince(Request $request) {
        try {
            $this->validate($request, [
                'jobTitleId' => 'required|integer',
                'monthsOfExpereince' => 'required|integer',
                'officeName' => 'required',
                'officeAddress' => 'required',
                'city' => 'required',
                'reference1Name'=>'sometimes',
                'reference1Mobile'=>'required_with:reference1Name',
                'reference1Email' => 'required_with:reference1Name',
                'reference2Name'=>'sometimes',
                'reference2Mobile'=>'required_with:reference2Name',
                'reference2Email' => 'required_with:reference2Name',
                
            ]);
            
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $workExp = new WorkExperience();
            if (isset($request->id) && !empty($request->id)) {
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
            return apiResponse::customJsonResponse(1, 200, "data Saved successfully", $data);
            
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError("Request validation failed.", ["data" => trans("messages.something_wrong")]);
        }
        
        
        
    }

    /**
     * Description : To Delete work experience
     * Method : deleteWorkExperince
     * formMethod : DELETE
     * @param Request $request
     * @return type
     */
    public function deleteWorkExperince(Request $request) {
        try {
            WorkExperience::where('id', $request->id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            return apiResponse::customJsonResponse(1, 200, "Deleted successfully");
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
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
            
            return apiResponse::customJsonResponse(1, 200, trans("messages.work_exp_added"), $query);
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        }
    }

}
