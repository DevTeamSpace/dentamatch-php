<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\Affiliation;
use App\Helpers\ApiResponse;
use App\Models\JobSeekerAffiliation;

class AffiliationsApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('ApiAuth');
    }

    /**
     * Description : Show affiliation lists with jobseeker affiliations
     * Method : getAffiliationList
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getAffiliationList(Request $request)
    {
        try {
            $data = [];
            $jobSeekerAffiliationData = [];
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                $affiliationList = Affiliation::getAffiliationList();
                $jobseekerAffiliation = JobSeekerAffiliation::getUserAffiliationList($userId);

                if (!empty($jobseekerAffiliation)) {
                    foreach ($jobseekerAffiliation as $key => $value) {
                        $jobSeekerAffiliationData[$value['affiliationId']] = ['affiliationId' => $value['affiliationId'], 'otherAffiliation' => $value['otherAffiliation']];
                    }
                }

                if (!empty($affiliationList)) {
                    foreach ($affiliationList as $key => $value) {
                        $data[$key]['affiliationId'] = $value['affiliationId'];
                        $data[$key]['affiliationName'] = $value['affiliationName'];
                        $data[$key]['otherAffiliation'] = !empty($jobSeekerAffiliationData[$value['affiliationId']]['otherAffiliation']) ? $jobSeekerAffiliationData[$value['affiliationId']]['otherAffiliation'] : null;
                        $data[$key]['jobSeekerAffiliationStatus'] = !empty($jobSeekerAffiliationData[$value['affiliationId']]) ? 1 : 0;
                    }
                }

                $return['list'] = array_values($data);

                $returnResponse = ApiResponse::customJsonResponse(1, 200, trans("messages.affiliation_list_success"), ApiResponse::convertToCamelCase($return));
            } else {
                $returnResponse = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (\Exception $e) {
            Log::error($e);
            $returnResponse = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }

        return $returnResponse;
    }

    /**
     * Description : Update user affiliations
     * Method : postUpdateUserSkills
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postAffiliationSaveUpdate(Request $request)
    {
        try {
            $this->validate($request, [
                'affiliationDataArray' => 'sometimes',
                'other'                => 'sometimes',
            ]);

            $reqData = $request->all();
            $userId = $request->userServerData->user_id;
            $jobSeekerData = [];
            $keyCount = 0;

            if ($userId > 0) {
                if ((!empty($reqData['affiliationDataArray']) && is_array($reqData['affiliationDataArray'])) || (!empty($reqData['other']) && is_array($reqData['other']))) {
                    JobSeekerAffiliation::where('user_id', '=', $userId)->forceDelete();
                }

                if (!empty($reqData['affiliationDataArray']) && is_array($reqData['affiliationDataArray'])) {
                    foreach ($reqData['affiliationDataArray'] as $key => $value) {
                        if (!empty($value)) {
                            $jobSeekerData[$key]['affiliation_id'] = $value;
                            $jobSeekerData[$key]['user_id'] = $userId;
                            $jobSeekerData[$key]['other_affiliation'] = null;
                        }
                        $keyCount = $key + 1;
                    }
                }

                if (!empty($reqData['other']) && is_array($reqData['other'])) {
                    foreach ($reqData['other'] as $otherAffiliation) {
                        if (!empty($otherAffiliation['affiliationId'])) {
                            $jobSeekerData[$keyCount]['affiliation_id'] = $otherAffiliation['affiliationId'];
                            $jobSeekerData[$keyCount]['user_id'] = $userId;
                            $jobSeekerData[$keyCount]['other_affiliation'] = $otherAffiliation['otherAffiliation'];
                        }
                    }
                }
                if (!empty($jobSeekerData)) {
                    JobSeekerAffiliation::insert($jobSeekerData);
                }
                ApiResponse::chkProfileComplete($userId);
                $returnResponse = ApiResponse::customJsonResponse(1, 200, trans("messages.affiliation_add_success"));
            } else {
                $returnResponse = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $returnResponse = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            $returnResponse = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }

        return $returnResponse;
    }
}
