<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use App\Models\UserProfile;
use App\Models\JobSeekerTempAvailability;
use App\Models\JobLists;
use App\Models\JobseekerTempHired;

class CalendarApiController extends Controller {
    
    public function __construct() {
        $this->middleware('ApiAuth');
    }
    
    /**
     * Description : Post availability for job
     * Method : postJobAvailability
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postJobAvailability(Request $request){
        try{
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $userProfileModel = UserProfile::where('user_id', $userId)->first();
                $countExistingjob = 0;
                // check if job seeker is already hired for any temp job for these dates
                $requestTempDates = $reqData['tempdDates'];
                $tempDate = [];
                if(count($requestTempDates) > 0){
                    $tempAvailability = JobseekerTempHired::where('jobseeker_id',$userId)->where('job_date','>=',date('Y-m-d'))->select('job_date')->get();
                    if($tempAvailability){
                        $tempDateArray = $tempAvailability->toArray();
                        foreach($tempDateArray as $value) {
                            $tempDate[] = $value['job_date'];
                        }
                    }
                }
                
                if($countExistingjob == 0){
                        $userProfileModel->is_fulltime = $reqData['isFulltime'];
                        $userProfileModel->is_parttime_monday = 0;
                        $userProfileModel->is_parttime_tuesday = 0;
                        $userProfileModel->is_parttime_wednesday = 0;
                        $userProfileModel->is_parttime_thursday = 0;
                        $userProfileModel->is_parttime_friday = 0;
                        $userProfileModel->is_parttime_saturday = 0;
                        $userProfileModel->is_parttime_sunday = 0;
                        $userProfileModel->save();
                        if(is_array($reqData['partTimeDays']) && (count($reqData['partTimeDays']) > 0)){
                            foreach($reqData['partTimeDays'] as $value){
                                $field = 'is_parttime_'.$value;
                                $userProfileModel->$field = 1;
                            }
                        }
                        $userProfileModel->save();
                        $deleteAllAvailabilitySet = JobSeekerTempAvailability::where('user_id', '=', $userId);
                        if(!empty($tempDate)) {
                            $deleteAllAvailabilitySet = $deleteAllAvailabilitySet->whereNotIn('temp_job_date', $tempDate);
                        }
                        $deleteAllAvailabilitySet->where('temp_job_date','>=',date('Y-m-d'))->forceDelete();
                        
                        if(is_array($requestTempDates) && count($requestTempDates) > 0) {
                            $tempDateArray = [];
                            
                            $insertTempDateArray = array_diff($requestTempDates, $tempDate);
                            if(!empty($insertTempDateArray)) {
                                foreach($insertTempDateArray as $newTempDate) {     
                                        $tempDateArray[] = array('user_id' => $userId , 'temp_job_date' => $newTempDate);
                                }
                                JobSeekerTempAvailability::insert($tempDateArray);
                            }
                        }
                        ApiResponse::chkProfileComplete($userId);
                        $response = ApiResponse::customJsonResponse(1, 200, trans("messages.availability_add_success"));
                }
            }else{
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    /**
     * Description : Post Hired Jobs By Date
     * Method : postHiredJobsByDate
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postHiredJobsByDate(Request $request)
    {
        try{
            $this->validate($request, [
                'jobStartDate' => 'required',
                'jobEndDate' => 'required'
            ]);
            
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $jobStartDate = $reqData['jobStartDate'];
                $jobEndDate = $reqData['jobEndDate'];
                $listHiredJobs = JobLists::postJobCalendar($userId, $jobStartDate, $jobEndDate);
                
                if(count($listHiredJobs) > 0 && count($listHiredJobs['list']) > 0){
                    $response = ApiResponse::customJsonResponse(1, 200, trans("messages.job_search_list"),  ApiResponse::convertToCamelCase($listHiredJobs));
                }else{
                    $response = ApiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                }
                
            }else{
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
    
    /**
     * Description : List Calendar Availability
     * Method : postAvailability
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function postAvailability(Request $request)
    {
        try{
            $this->validate($request, [
                'calendarStartDate' => 'required',
                'calendarEndDate' => 'required'
            ]);
            
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $calendarStartDate = $reqData['calendarStartDate'];
                $calendarEndDate = $reqData['calendarEndDate'];
                $listAvailability = UserProfile::getAvailability($userId, $calendarStartDate, $calendarEndDate);
                if(count($listAvailability) > 0){
                    $response = ApiResponse::customJsonResponse(1, 200, "",  ApiResponse::convertToCamelCase($listAvailability));
                }else{
                    $response = ApiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                }
                
            }else{
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
}