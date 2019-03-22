<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\PartTime;
use App\Http\Controllers\Controller;
use App\Models\JobSeekerProfiles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\UserProfile;
use App\Models\JobSeekerTempAvailability;
use App\Models\JobLists;

class CalendarApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(['ApiAuth', 'ApiLog']);
    }

    /**
     * Description : Post availability dates for jobseeker
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function postAvailabilityDates(Request $request)
    {
        $this->validate($request, [
            'tempdDates'   => 'present|array',
            'tempdDates.*' => 'date|date_format:Y-m-d',
            'partTimeDays' => 'present|array'
        ]);

        $userId = $request->apiUserId;
        $jobSeeker = JobSeekerProfiles::where('user_id', $userId)->first();
        // check if job seeker is already hired for any temp job for these dates
        $requestTempDates = $request->input('tempdDates');
        $futureJobTempDates = [];
        if ($requestTempDates) {
            $futureJobTempDates = $jobSeeker->tempJobsHired()
                ->where('job_date', '>=', date('Y-m-d'))
                ->select('job_date')->get()->pluck('job_date')->toArray();
        }

        $jobSeeker->is_fulltime = $request->input('isFulltime', 0);

        $partTimeDays = $request->input('partTimeDays', []);
        foreach (PartTime::days() as $day) {
            $field = "is_parttime_$day";
            $jobSeeker->$field = in_array($day, $partTimeDays) ? 1 : 0;
        }

        $jobSeeker->save();

        $jobSeeker->tempDates()
            ->whereNotIn('temp_job_date', $futureJobTempDates)
            ->where('temp_job_date', '>=', date('Y-m-d'))
            ->forceDelete();

        if ($requestTempDates) {
            $tempDateArray = [];

            $insertTempDates = array_diff($requestTempDates, $futureJobTempDates);
            $insertTempDates = array_filter($insertTempDates, function ($item) {
                return $item >= date('Y-m-d');
            });

            foreach ($insertTempDates as $newTempDate) {
                $tempDateArray[] = ['user_id' => $userId, 'temp_job_date' => $newTempDate];
            }
            JobSeekerTempAvailability::insert($tempDateArray);
        }
        ApiResponse::chkProfileComplete($userId);
        return ApiResponse::successResponse(trans("messages.availability_add_success"));

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