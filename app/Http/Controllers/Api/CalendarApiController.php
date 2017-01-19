<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Helpers\apiResponse;
use App\Models\UserProfile;
use App\Models\JobSeekerTempAvailability;

class CalendarApiController extends Controller {
    
    public function __construct() {
        
    }
    public function postJobAvailability(Request $request){
        try{
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                $userProfileModel = UserProfile::where('user_id', $userId)->first();
                $userProfileModel->is_fulltime = $reqData['isFulltime'];
                if(is_array($reqData['partTimeDays']) && (count($reqData['partTimeDays']) > 0)){
                    foreach($reqData['partTimeDays'] as $key => $value){
                        $field = 'is_parttime_'.$key;
                        $userProfileModel->$field = $value;
                    }
                }
                $userProfileModel->save();
                if(is_array($reqData['tempdDates']) && count($reqData['tempdDates']) > 0){
                    JobSeekerTempAvailability::where('user_id', '=', $userId)->forceDelete();
                    $tempDateArray = array();
                    foreach($reqData['tempdDates'] as $tempDate){
                        $tempDateArray[] = array('user_id' => $userId , 'temp_job_date' => $tempDate);
                    }
                    JobSeekerTempAvailability::insert($tempDateArray);
                }
                $response = apiResponse::customJsonResponse(1, 200, trans("messages.availability_add_success"));
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