<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\apiResponse;
use App\Models\UserProfile;
use App\Models\JobSeekerTempAvailability;
use App\Models\JobLists;

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
        //try{
            $userId = $request->userServerData->user_id;
            if($userId > 0){
                $reqData = $request->all();
                $userProfileModel = UserProfile::where('user_id', $userId)->first();
                $jobCount = JobLists::where('seeker_id','=',$userId)->whereIn('applied_status',[JobLists::HIRED])->get()->count();
                if($jobCount == 0){
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
                        JobSeekerTempAvailability::where('user_id', '=', $userId)->where('temp_job_date','>=',date('Y-m-d'))->forceDelete();
                        
                        if(is_array($reqData['tempdDates']) && count($reqData['tempdDates']) > 0) {
                            $updateTempDates = [];
                            $tempDateArray = [];
                            $tempJobDate = [];
                            $availability = JobSeekerTempAvailability::select('temp_job_date')->whereIn('temp_job_date',$reqData['tempdDates'])->where('user_id', '=', $userId)->get();
                            if($availability) {
                                $tempJobDate = $availability->toArray();
                                foreach($tempJobDate as $value){
                                    $updateTempDates[] = $value['temp_job_date'];
                                }
                            }
                            $insertTempDateArray = array_diff($reqData['tempdDates'], $updateTempDates);
                            
                            if(!empty($insertTempDateArray)) {
                                foreach($insertTempDateArray as $tempDate) {     
                                        $tempDateArray[] = array('user_id' => $userId , 'temp_job_date' => $tempDate);
                                }
                                JobSeekerTempAvailability::insert($tempDateArray);
                            }
                        }
                        $response = apiResponse::customJsonResponse(1, 200, trans("messages.availability_add_success"));
                }else{
                        $response = apiResponse::customJsonResponse(0, 201, trans("messages.already_job_availability"));
                }
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
       /* } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }*/
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
                if(count($listHiredJobs['list']) > 0){
                    $response = apiResponse::customJsonResponse(1, 200, trans("messages.job_search_list"),  apiResponse::convertToCamelCase($listHiredJobs));
                }else{
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                }
                
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
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
                    $response = apiResponse::customJsonResponse(1, 200, "",  apiResponse::convertToCamelCase($listAvailability));
                }else{
                    $response = apiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                }
                
            }else{
                $response = apiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            } 
        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            $response = apiResponse::responseError(trans("messages.validation_failure"), ["data" => $messages]);
        } catch (\Exception $e) {
            $response = apiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }
}