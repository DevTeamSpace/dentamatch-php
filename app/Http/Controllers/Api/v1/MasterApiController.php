<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\JobTitles;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\State;
use App\Models\PreferredJobLocation;
use Illuminate\Http\Response;

class MasterApiController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Description : Get skills list
     * Method : getSkilllists
     * formMethod : GET
     * @param Request $request
     * @return Response
     * todo not used?
     */
//    public function getSkilllists()
//    {
//        $skill_lists = Skills::whereNull('parent_id')->with('children')->get();
//        $response = ApiResponse::customJsonResponseObject(1, 200, "Preferred Job Location list", 'skillList', ApiResponse::convertToCamelCase($skill_lists));
//        return $response;
//    }

    /**
     * Description : Get states list
     * Method : getStates
     * formMethod : GET
     * @return Response
     */
    public function getStates()
    {
        $stateObj = State::select('state_name');
        if (request()->get('q')) {
            $stateObj->whereRaw("state_name like ?", ["%" . request()->get('q') . "%"]);
        }
        $state_list = $stateObj->get();
        return ApiResponse::successResponse('States list', ['state_list' => $state_list]);
    }

    /**
     * Description : Get Preferred Job Location list
     * Method : getPreferredJobLocations
     * formMethod : GET
     * @return Response
     */
    public function getPreferredJobLocations()
    {
        $locations = PreferredJobLocation::getAllPreferredJobLocation();
        return ApiResponse::successResponse('Locations', ['preferredJobLocations' => $locations]);
    }

    /**
     * Description : Get Job Titles
     * Method : getJobTitles
     * formMethod : Get
     * @param
     * @return Response
     */
    public function getJobTitles()
    {
        $job_title = JobTitles::where('is_active', 1)->orderby('id')->get()->toArray();
        return ApiResponse::successResponse('Jobtitle list', ['joblists' => $job_title]);
    }

    /**
     * Description : Get Terms and Conditions
     * Method : getTermsAndCondition
     * formMethod : GET
     */
    public function getTermsAndCondition()
    {
        return view('terms-and-condition');
    }

    /**
     * Description : Get Privacy Policy
     * Method : getPrivacyPolicy
     * formMethod : GET
     */
    public function getPrivacyPolicy()
    {
        return view('privacy-policy');
    }

}