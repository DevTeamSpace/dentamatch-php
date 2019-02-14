<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\JobType;
use App\Http\Controllers\Controller;
use App\Utils\PushNotificationService;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Notification;
use App\Models\RecruiterJobs;
use App\Models\Device;
use App\Models\UserChat;
use App\Models\JobSeekerTempAvailability;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PushNotificationApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('ApiAuth', ['except' => ['userChatNotification']]);
    }

    /**
     * Description : Get notification list
     * Method : getNotificationListing
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function getNotificationlists(Request $request)
    {
        try {
            $this->validate($request, [
                'page' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                $reqData = $request->all();
                $reqData['userId'] = $userId;
                $userAvailability = JobSeekerTempAvailability::where('user_id', $userId)->pluck('temp_job_date')->toArray();
                $notificationList = Notification::userNotificationList($reqData);
                if (count($notificationList['list']) > 0) {
                    foreach ($notificationList['list'] as $key => $notification) {
                        if ($notification['job_list_id'] && $notification['job_list_id'] > 0) {
                            $data = RecruiterJobs::getJobDetail($notification['job_list_id'], $userId);
                            if (!empty($data)) {
                                $notification['job_details'] = $data;
                                $notification['currentAvailability'] = [];
                                if ($data['job_type'] == JobType::TEMPORARY) {
                                    $notification['currentAvailability'] = array_values(array_intersect($userAvailability, $data['job_type_dates']));
                                    if (count($notification['currentAvailability']) == 0) {
                                        $dateMsgString = [];
                                        foreach ($data['job_type_dates'] as $jobDate) {
                                            $dateMsgString[] = date('d M', strtotime($jobDate));
                                        }
                                        $message = str_replace('##DATES##', implode($dateMsgString, ','), trans("messages.seeker_set_availabilty"));
                                        $notificationList['list'][$key]['notification_data'] .= $message;
                                    }
                                }
                            }
                        }
                    }
                    $response = ApiResponse::customJsonResponse(1, 200, trans("messages.notification_list"), ApiResponse::convertToCamelCase($notificationList));
                } else {
                    $response = ApiResponse::customJsonResponse(0, 201, trans("messages.no_data_found"));
                }
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            Log::error($e);
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
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

    public function PostUpdateNotification(Request $request)
    {
        try {
            $this->validate($request, [
                'notificationId' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                $reqData = $request->all();
                Notification::where('id', $reqData['notificationId'])->update(['seen' => 1]);
                $response = ApiResponse::customJsonResponse(1, 200, trans("messages.data_saved_success"));
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            Log::error($e);
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : send chat push notification (from nodejs chat)
     * formMethod : POST
     * @param Request $request
     */
    public function userChatNotification(Request $request)
    {
        $this->success = 0;
        try {
            $requestData = $request->all();
            $validateKeys = ['fromId'   => 'required', 'toId' => 'required',
                             'fromName' => 'required', 'message' => 'required',
                             'sentTime' => 'required', 'messageId' => 'required'];
            if (isset($request['recruiterId'])) {
                $validateKeys = ['name'          => 'required', 'recruiterId' => 'required', 'message' => 'required',
                                 'messageListId' => 'required', 'seekerId' => 'required', 'messageId' => 'required',
                                 'timestamp'     => 'required', 'recruiterBlock' => 'required', 'seekerBlock' => 'required'];
                $requestData['toId'] = $requestData['seekerId'];
            }
            $this->validate($request, $validateKeys);
            $deviceModel = Device::getDeviceToken($requestData['toId']);
            if ($deviceModel) {
                PushNotificationService::send($deviceModel, $requestData['message'], ["data" => $requestData], $requestData['toId']);
            }
        } catch (ValidationException $e) {
            return ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    /**
     * Description : Update device token
     * Method : Update device token
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function PostUpdateDeviceToken(Request $request)
    {
        try {
            $this->validate($request, [
                'updateDeviceToken' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            $reqData = $request->all();
            if ($userId > 0) {
                Device::where('user_id', $userId)->update(['device_token' => $reqData['updateDeviceToken']]);
                $response = ApiResponse::customJsonResponse(1, 200, trans("messages.update_device_token"));
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : Delete notification
     * Method : Delete notification
     * formMethod : POST
     * @param Request $request
     * @return type
     */
    public function PostDeleteNotification(Request $request)
    {
        try {
            $this->validate($request, [
                'notificationId' => 'required',
            ]);
            $userId = $request->userServerData->user_id;
            $reqData = $request->all();
            if ($userId > 0) {
                Notification::findOrFail($reqData['notificationId'])->delete();
                $response = ApiResponse::customJsonResponse(1, 200, trans("messages.notification_delete"));
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (ValidationException $e) {
            $response = ApiResponse::responseError(trans("messages.validation_failure"), ["data" => $e->errors()]);
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    /**
     * Description : Get unread notification
     * Method : get unread notification
     * formMethod : GET
     * @param Request $request
     * @return type
     */
    public function GetunreadNotification(Request $request)
    {
        try {
            $userId = $request->userServerData->user_id;
            if ($userId > 0) {
                $query = Notification::where('receiver_id', '=', $userId)->where('seen', '=', 0);
                $total = $query->count();
                $unread['notificationCount'] = $total;
                $response = ApiResponse::customJsonResponse(1, 200, "", $unread);
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

    public function userChatDelete(Request $request)
    {
        try {
            $userId = $request->userServerData->user_id;
            $this->validate($request, [
                'recruiterId' => 'required',
            ]);
            if ($userId > 0) {
                UserChat::where(function ($query) use ($userId, $request) {
                    $query->where('from_id', $userId)->where('to_id', $request->recruiterId);
                })->orwhere(function ($query) use ($userId, $request) {
                    $query->where('to_id', $userId)->where('from_id', $request->recruiterId);
                })->delete();
                $response = ApiResponse::customJsonResponse(1, 200);
            } else {
                $response = ApiResponse::customJsonResponse(0, 204, trans("messages.invalid_token"));
            }
        } catch (\Exception $e) {
            $response = ApiResponse::responseError(trans("messages.something_wrong"), ["data" => $e->getMessage()]);
        }
        return $response;
    }

}