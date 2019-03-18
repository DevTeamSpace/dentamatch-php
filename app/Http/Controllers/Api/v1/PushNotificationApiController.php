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
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PushNotificationApiController extends Controller
{
    public function __construct()
    {
         $this->middleware(['ApiAuth', 'ApiLog'], ['except' => ['userChatNotification']]);
    }

    /**
     * Description : Get notifications
     * Method : getNotifications
     * formMethod : GET
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function getNotifications(Request $request)
    {
        $this->validate($request, [
            'page' => 'required',
        ]);
        $userId = $request->apiUserId;
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
            return ApiResponse::successResponse(trans("messages.notification_list"), $notificationList);
        }

        return ApiResponse::noDataResponse();
    }

    /**
     * Description : Read message
     * Method : Read unread notification
     * formMethod : POST
     * @param Request $request
     * @return Response
     * todo anyone can 'read' any notification
     * @throws ValidationException
     */

    public function updateNotification(Request $request)
    {
        $this->validate($request, [
            'notificationId' => 'required',
        ]);
        Notification::where('id', $request->input('notificationId'))->update(['seen' => 1]);
        return ApiResponse::successResponse(trans("messages.data_saved_success"));
    }

    /**
     * Description : send chat push notification (from nodejs chat)
     * formMethod : POST
     * @param Request $request
     * @return array|string
     * @throws ValidationException
     */
    public function userChatNotification(Request $request)
    {
        $this->success = 0;
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
    }

    /**
     * Description : Update device token
     * Method : Update device token
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function PostUpdateDeviceToken(Request $request)
    {
        $this->validate($request, [
            'updateDeviceToken' => 'required',
        ]);
        Device::where('user_id', $request->apiUserId)->update(['device_token' => $request->input('updateDeviceToken')]);
        return ApiResponse::successResponse(trans("messages.update_device_token"));
    }

    /**
     * Description : Delete notification
     * Method : Delete notification
     * formMethod : POST
     * @param Request $request
     * @return Response
     * @throws ValidationException|\Exception
     * todo anyone can delete any notification
     * todo delete without finding
     */
    public function PostDeleteNotification(Request $request)
    {
        $this->validate($request, [
            'notificationId' => 'required',
        ]);
        Notification::findOrFail($request->input('notificationId'))->delete();
        return ApiResponse::successResponse(trans("messages.notification_delete"));
    }

    /**
     * Description : Get unread notification
     * Method : get unread notification
     * formMethod : GET
     * @param Request $request
     * @return Response
     */
    public function getUnreadCount(Request $request)
    {
        $userId = $request->apiUserId;
        $total = Notification::where('receiver_id', $userId)->where('seen', 0)->count();
        return ApiResponse::successResponse("", ['notificationCount' => $total]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function userChatDelete(Request $request)
    {
        $userId = $request->apiUserId;
        $this->validate($request, [
            'recruiterId' => 'required',
        ]);
        $recruiterId = $request->input('recruiterId');

        UserChat::where(function ($query) use ($userId, $recruiterId) {
            $query->where('from_id', $userId)->where('to_id', $recruiterId);
        })->orwhere(function ($query) use ($userId, $recruiterId) {
            $query->where('to_id', $userId)->where('from_id', $recruiterId);
        })->delete();

        return ApiResponse::successResponse();
    }

}