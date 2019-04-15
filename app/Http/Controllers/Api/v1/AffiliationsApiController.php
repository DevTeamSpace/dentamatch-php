<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Affiliation;
use App\Helpers\ApiResponse;
use App\Models\JobSeekerAffiliation;

class AffiliationsApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Show affiliation lists with jobseeker affiliations
     * Method : getAffiliationList
     * formMethod : GET
     * @param Request $request
     * @return Response
     */
    public function getAffiliationList(Request $request)
    {
        $data = [];
        $jobSeekerAffiliationData = [];
        $affiliationList = Affiliation::getAffiliationList();
        $jobseekerAffiliation = JobSeekerAffiliation::getUserAffiliationList($request->apiUserId);

        foreach ($jobseekerAffiliation as $key => $value) {
            $jobSeekerAffiliationData[$value['affiliationId']] = ['affiliationId' => $value['affiliationId'], 'otherAffiliation' => $value['otherAffiliation']];
        }

        foreach ($affiliationList as $key => $value) {
            $data[$key]['affiliationId'] = $value['affiliationId'];
            $data[$key]['affiliationName'] = $value['affiliationName'];
            $data[$key]['otherAffiliation'] = !empty($jobSeekerAffiliationData[$value['affiliationId']]['otherAffiliation']) ? $jobSeekerAffiliationData[$value['affiliationId']]['otherAffiliation'] : null;
            $data[$key]['jobSeekerAffiliationStatus'] = !empty($jobSeekerAffiliationData[$value['affiliationId']]) ? 1 : 0;
        }

        $return['list'] = array_values($data);

        return ApiResponse::successResponse(trans("messages.affiliation_list_success"), $return);

    }

    /**
     * Description : Update user affiliations
     * Method : postUpdateUserSkills
     * formMethod : POST
     * @param Request $request
     * @return Response
     */
    public function postAffiliationSaveUpdate(Request $request)
    {
        $reqData = $request->all();
        $userId = $request->apiUserId;
        $jobSeekerData = [];
        $keyCount = 0;

        if ((!empty($reqData['affiliationDataArray']) && is_array($reqData['affiliationDataArray'])) || (!empty($reqData['other']) && is_array($reqData['other']))) {
            JobSeekerAffiliation::where('user_id', '=', $userId)->delete();
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
        return ApiResponse::successResponse(trans("messages.affiliation_add_success"));
    }
}
