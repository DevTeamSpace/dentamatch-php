<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\UserProfile;
use App\Models\JobSeekerTempAvailability;
use App\Models\JobLists;
use App\Models\JobseekerTempHired;

class CalendarApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Post availability for job
     * Method : postJobAvailability
     * formMethod : POST
     * @param Request $request
     * @return Response
     */
    public function postAvailabilityDates(Request $request)
    {
        $userId = $request->apiUserId;
        $reqData = $request->all();
        $userProfileModel = UserProfile::where('user_id', $userId)->first();
        $countExistingjob = 0;
        // check if job seeker is already hired for any temp job for these dates
        $requestTempDates = $reqData['tempdDates'];
        $tempDate = [];
        if (count($requestTempDates) > 0) {
            $tempAvailability = JobseekerTempHired::where('jobseeker_id', $userId)
                ->where('job_date', '>=', date('Y-m-d'))
                ->select('job_date')->get();
            if ($tempAvailability) {
                $tempDateArray = $tempAvailability->toArray();
                foreach ($tempDateArray as $value) {
                    $tempDate[] = $value['job_date'];
                }
            }
        }

        if ($countExistingjob == 0) {
            $userProfileModel->is_fulltime = $reqData['isFulltime'];
            $userProfileModel->is_parttime_monday = 0;
            $userProfileModel->is_parttime_tuesday = 0;
            $userProfileModel->is_parttime_wednesday = 0;
            $userProfileModel->is_parttime_thursday = 0;
            $userProfileModel->is_parttime_friday = 0;
            $userProfileModel->is_parttime_saturday = 0;
            $userProfileModel->is_parttime_sunday = 0;
            $userProfileModel->save();
            if (is_array($reqData['partTimeDays']) && (count($reqData['partTimeDays']) > 0)) {
                foreach ($reqData['partTimeDays'] as $value) {
                    $field = 'is_parttime_' . $value;
                    $userProfileModel->$field = 1;
                }
            }
            $userProfileModel->save();
            $deleteAllAvailabilitySet = JobSeekerTempAvailability::where('user_id', '=', $userId);
            if (!empty($tempDate)) {
                $deleteAllAvailabilitySet = $deleteAllAvailabilitySet->whereNotIn('temp_job_date', $tempDate);
            }
            $deleteAllAvailabilitySet->where('temp_job_date', '>=', date('Y-m-d'))->forceDelete();

            if (is_array($requestTempDates) && count($requestTempDates) > 0) {
                $tempDateArray = [];

                $insertTempDateArray = array_diff($requestTempDates, $tempDate);
                if (!empty($insertTempDateArray)) {
                    foreach ($insertTempDateArray as $newTempDate) {
                        $tempDateArray[] = ['user_id' => $userId, 'temp_job_date' => $newTempDate];
                    }
                    JobSeekerTempAvailability::insert($tempDateArray);
                }
            }
            ApiResponse::chkProfileComplete($userId);
            return ApiResponse::successResponse(trans("messages.availability_add_success"));
        }

    }

    /**
     * Description : Get JobSeeker's hired jobs by dates
     * Method : getHiredJobs
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function getHiredJobs(Request $request)
    {
        $this->validate($request, [
            'jobStartDate' => 'required',
            'jobEndDate'   => 'required'
        ]);

        $userId = $request->apiUserId;
        $jobStartDate = $request->input('jobStartDate');
        $jobEndDate = $request->input('jobEndDate');
        $listHiredJobs = JobLists::postJobCalendar($userId, $jobStartDate, $jobEndDate);

        if ($listHiredJobs && $listHiredJobs['total']) {
            return ApiResponse::successResponse(trans("messages.job_search_list"), $listHiredJobs);
        }

        return ApiResponse::noDataResponse();
    }

    /**
     * Description : Get JobSeeker's availability dates
     * Method : getAvailabilityDates
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function getAvailabilityDates(Request $request)
    {
        $this->validate($request, [
            'calendarStartDate' => 'required',
            'calendarEndDate'   => 'required'
        ]);

        $userId = $request->apiUserId;
        $calendarStartDate = $request->input('calendarStartDate');
        $calendarEndDate = $request->input('calendarEndDate');
        $listAvailability = UserProfile::getAvailability($userId, $calendarStartDate, $calendarEndDate);

        return ApiResponse::successResponse("", $listAvailability);
    }
}