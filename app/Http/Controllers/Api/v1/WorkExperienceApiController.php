<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Models\WorkExperience;
use App\Helpers\ApiResponse;

class WorkExperienceApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Get JobSeeker'e Work Experience
     * Method : getWorkExperience
     * formMethod : POST
     * @param Request $request
     * @return Response
     */
    public function getWorkExperience(Request $request)
    {
        $start = (int)$request->input('start', 0);
        $limit = (int)$request->input('limit', config('app.defaul_product_per_page'));

        $userId = $request->apiUserId;

        $query = WorkExperience::getWorkExperienceList($userId, $start, $limit);
        $query['start'] = $start;
        $query['limit'] = $limit;

        return ApiResponse::successResponse(trans("messages.work_exp_list"), $query);
    }

    /**
     * Description : Add work experience
     * Method : postWorkExperience
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postWorkExperience(Request $request)
    {
        $this->validate($request, [
            'jobTitleId'         => 'required|integer',
            'monthsOfExpereince' => 'required|integer',
            'officeName'         => 'required',
            'officeAddress'      => 'required',
            'state'              => 'required',
            'city'               => 'required',
            'reference1Name'     => 'sometimes',
            'reference1Mobile'   => 'sometimes',
            'reference1Email'    => 'sometimes|email',
            'reference2Name'     => 'sometimes',
            'reference2Mobile'   => 'sometimes',
            'reference2Email'    => 'sometimes|email',
            'action'             => 'required|in:add,edit',
            'id'                 => 'integer|required_if:action,edit'
        ]);
        $userId = $request->apiUserId;
        $workExp = new WorkExperience();
        if ($request->action == "edit" && !empty($request->id)) {
            $workExp = WorkExperience::find($request->id);
        }

        $workExp->user_id = $userId;
        $workExp->job_title_id = $request->jobTitleId;
        $workExp->months_of_expereince = $request->monthsOfExpereince;
        $workExp->office_name = $request->officeName;
        $workExp->office_address = $request->officeAddress;
        $workExp->state = $request->state;
        $workExp->city = $request->city;
        $workExp->reference1_name = $request->reference1Name;
        $workExp->reference1_mobile = $request->reference1Mobile;
        $workExp->reference1_email = $request->reference1Email;
        $workExp->reference2_name = $request->reference2Name;
        $workExp->reference2_mobile = $request->reference2Mobile;
        $workExp->reference2_email = $request->reference2Email;
        $workExp->deleted_at = null;
        $workExp->save();

        $data['list'][] = $workExp;
        if ($request->action == "edit") {
            $message = trans("messages.work_exp_updated");
        } else {
            $message = trans("messages.work_exp_added");
        }
        ApiResponse::chkProfileComplete($userId);
        return ApiResponse::successResponse($message, $data);
    }

    /**
     * Description : Delete work experience
     * Method : deleteWorkExperience
     * formMethod : DELETE
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function deleteWorkExperience(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer'
        ]);

        $userId = $request->apiUserId;
        WorkExperience::where('id', $request->id)->where('user_id', $userId)->update(['deleted_at' => date('Y-m-d H:i:s')]);
        return ApiResponse::successResponse(trans("messages.work_exp_removed"));
    }
}
