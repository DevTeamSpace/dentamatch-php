<?php
namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\apiResponse;
use App\Models\Notification;
use App\Models\JobLists;
use App\Models\RecruiterJobs;
use App\Models\Device;

class PushNotificationApiController extends Controller {
    
    public function __construct() {
        
    }
    
    /**
     * Description : Get notification list
     * Method : getNotificationListing
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getNotificationlists(Request $request){
        try{
            $this->validate($request, [
                'page' => 'required',
            ]);
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                $reqData['userId'] = $userId;
                $notificationList = Notification::userNotificationList($reqData);
                $updated_notification = array();
                    if(count($notificationList['list']) > 0){
                        foreach($notificationList['list'] as $notification){
                            if($notification['job_list_id'] && $notification['job_list_id'] > 0){
                                //$jobList = JobLists::select('recruiter_job_id')->where('id','=',$notification['job_list_id'])->first();
                                $data = RecruiterJobs::getJobDetail($notification['job_list_id'], $userId); 
                                $notification['job_details'] = $data;
                            }
                            $updated_notification[] = $notification;
                        }
                        $response = apiResponse::customJsonResponse(1, 200, trans("messages.notification_list"),  apiResponse::convertToCamelCase($notificationList));
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
     * Description : Read message 
     * Method : Read unread notification 
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    
    public function PostUpdateNotification(Request $request){
        try{
            $this->validate($request, [
                'notificationId' => 'required',
            ]);
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            if($userId > 0){
                $reqData = $request->all();
                Notification::where('id', $reqData['notificationId'])->update(['seen' => 1]);
                $response =  apiResponse::customJsonResponse(1, 200, trans("messages.data_saved_success"));
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
     * Description : Update device token
     * Method : Update device token
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function PostUpdateDeviceToken(Request $request){
        try{
            $this->validate($request, [
                'updateDeviceToken' => 'required',
            ]);
            $userId = apiResponse::loginUserId($request->header('accessToken'));
            $reqData = $request->all();
            if($userId > 0){
                Device::where('user_id', $userId)->update(['device_token' => $reqData['updateDeviceToken']]);
                $response =  apiResponse::customJsonResponse(1, 200, trans("messages.update_device_token"));
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