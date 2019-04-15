<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Models\Schooling;
use App\Helpers\ApiResponse;
use App\Models\JobSeekerSchooling;

class SchoolingApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Get School List with JobSeeker Status for all schools
     * Method : getSchoolList
     * formMethod : GET
     * @param Request $request
     * @return Response
     */
    public function getSchoolList(Request $request)
    {
        $data = [];
        $jobSeekerData = [];
        $userId = $request->apiUserId;
        $schoolingList = Schooling::getSchoolingList();
        $jobseekerSchooling = JobSeekerSchooling::getUserSchoolingList($userId);

        if (!empty($jobseekerSchooling)) {
            foreach ($jobseekerSchooling as $key => $value) {
                $jobSeekerData[$value['schooling_id']] = ['schoolingId' => $value['schooling_id'], 'otherSchooling' => $value['other_schooling'], 'yearOfGraduation' => $value['year_of_graduation']];
            }
        }
        if (!empty($schoolingList)) {
            foreach ($schoolingList as $key => $value) {
                $data[$value['parentId']]['schoolingId'] = $value['parentId'];
                $data[$value['parentId']]['schoolName'] = $value['schoolName'];
                if (!empty($value['childId'])) {
                    $data[$value['parentId']]['schoolCategory'][] = ['schoolingId'      => $value['parentId'], 'schoolingChildId' => $value['childId'],
                                                                     'schoolChildName'  => $value['schoolChildName'], 'jobSeekerStatus' => !empty($jobSeekerData[$value['childId']]) ? 1 : 0,
                                                                     'otherSchooling'   => !empty($jobSeekerData[$value['childId']]) ? $jobSeekerData[$value['childId']]['otherSchooling'] : null,
                                                                     'yearOfGraduation' => !empty($jobSeekerData[$value['childId']]) ? $jobSeekerData[$value['childId']]['yearOfGraduation'] : null
                    ];
                } else {
                    $data[$value['parentId']]['schoolCategory'] = [];
                }
            }
        }

        $jobseekerKeys = array_keys($jobSeekerData);
        $schoolingKeys = array_keys($data);
        $intersectData = array_intersect($jobseekerKeys, $schoolingKeys);

        if (!empty($intersectData)) {
            foreach ($intersectData as $value) {
                $data[$value]['other'][] = ['schoolingId'      => $value, 'schoolingChildId' => $value,
                                            'schoolChildName'  => null, 'jobSeekerStatus' => 1,
                                            'otherSchooling'   => !empty($jobSeekerData[$value]) ? $jobSeekerData[$value]['otherSchooling'] : null,
                                            'yearOfGraduation' => !empty($jobSeekerData[$value]) ? $jobSeekerData[$value]['yearOfGraduation'] : null
                ];
            }
        }
        $return['list'] = array_values($data);

        return ApiResponse::successResponse(trans("messages.school_list_success"), $return);
    }

    /**
     * Description : Update JobSeeker Schooling
     * Method : postSchoolSaveUpdate
     * formMethod : POST
     * @param Request $request , schoolDataArray as an array
     * @return Response
     * @throws ValidationException
     */
    public function postSchoolSaveUpdate(Request $request)
    {
        $this->validate($request, [
            'schoolDataArray' => 'required'
        ]);

        $reqData = $request->all();
        $userId = $request->apiUserId;
        $jobSeekerData = [];

        if (!empty($reqData['schoolDataArray']) && is_array($reqData['schoolDataArray'])) {
            JobSeekerSchooling::where('user_id', $userId)->delete();

            foreach ($reqData['schoolDataArray'] as $key => $value) {
                if (!empty($value['schoolingChildId'])) {
                    $jobSeekerData[$key]['schooling_id'] = $value['schoolingChildId'];
                    $jobSeekerData[$key]['other_schooling'] = $value['otherSchooling'];
                    $jobSeekerData[$key]['year_of_graduation'] = $value['yearOfGraduation'];
                    $jobSeekerData[$key]['user_id'] = $userId;
                }
            }
        }

        if (!empty($jobSeekerData)) {
            JobSeekerSchooling::insert($jobSeekerData);
        }
        ApiResponse::chkProfileComplete($userId);
        return ApiResponse::successResponse(trans("messages.school_add_success"));

    }

}
