<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Affiliation;
use App\Helpers\apiResponse;
use App\Models\JobSeekerAffiliation;

class AffiliationsApiController extends Controller {

    public function __construct() {
        $this->middleware('ApiAuth');
    }
    
    /**
     * Description : Show affiliation lists with jobseeker affiliations
     * Method : getAffiliationList
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getAffiliationList(Request $request){
        try {
            $data = [];
            $jobSeekerAffiliationData=[];
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId>0) {
                $affiliationList = Affiliation::getAffiliationList();
                $jobseekerAffiliation = JobSeekerAffiliation::getUserAffiliationList($userId);

                if(!empty($jobseekerAffiliation)) {
                    foreach($jobseekerAffiliation as $key=>$value) {
                        $jobSeekerAffiliationData[$value['affiliationId']] = ['affiliationId' => $value['affiliationId'], 'otherAffiliation' => $value['otherAffiliation']];
                    }
                }

                if(!empty($affiliationList)) {
                    foreach($affiliationList as $key=>$value) {
                        $data[$key]['affiliationId']= $value['affiliationId'];
                        $data[$key]['affiliationName'] = $value['affiliationName'];
                        $data[$key]['otherAffiliation'] = $value['otherAffiliation'];
                        $data[$key]['jobSeekerAffiliationStatus'] = !empty($jobSeekerAffiliationData[$value['affiliationId']]) ? 1 : 0; 
                    }
                }

                $return['list'] = array_values($data);

                return apiResponse::customJsonResponse(1, 200, trans("messages.affiliation_list_success"), apiResponse::convertToCamelCase($return));
            } else {
                return apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token")); 
            }
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            return apiResponse::responseError("Request validation failed.", ["data" => $messages]);
        } catch (\Exception $e) {
            return apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
    }
    
    /**
     * Description : Update user affiliations
     * Method : postUpdateUserSkills
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postAffiliationSaveUpdate(Request $request) {
        try {
            $this->validate($request, [
                'affiliationDataArray' => 'sometimes',
                'other' => 'sometimes',
            ]);
            
            $reqData = $request->all();
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $jobSeekerData = [];
            $keyCount = 0;
            
            if($userId > 0){
                if((!empty($reqData['affiliationDataArray']) && is_array($reqData['affiliationDataArray'])) || (!empty($reqData['other']) && is_array($reqData['other']))){
                    $deletePreviousAffiliations = JobSeekerAffiliation::where('user_id', '=', $userId)->forceDelete();
                }
                
                if(!empty($reqData['affiliationDataArray']) && is_array($reqData['affiliationDataArray'])){
                    foreach($reqData['affiliationDataArray'] as $key=>$value) {
                        if(!empty($value['affiliationId'])) {
                            $jobSeekerData[$key]['affiliation_id'] = $value['affiliationId'];
                            $jobSeekerData[$key]['user_id'] = $userId;
                        }
                        $keyCount+=$key;
                    }
                }
                
                if(!empty($reqData['other']) && is_array($reqData['other'])){
                    foreach($reqData['other'] as $otherAffiliation){
                        $jobSeekerData[$keyCount]['affiliation_id'] = $otherAffiliation['affiliationId'];
                        $jobSeekerData[$keyCount]['user_id'] = $userId;
                        $jobSeekerData[$keyCount]['other_affiliation'] = $otherAffiliation['otherAffiliation'];
                    }
                }
                
                if(!empty($jobSeekerData)) {
                    JobSeekerAffiliation::insert($jobSeekerData);
                }
                
                return apiResponse::customJsonResponse(1, 200, trans("messages.affiliation_add_success")); 
            } else {
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
