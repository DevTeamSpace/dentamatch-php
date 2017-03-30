<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Device;
use App\Models\Notification;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\AppMessage;
use App\Models\User;
/**
 * NotificationServiceProvider class contains methods for notification management
 */
class NotificationServiceProvider extends ServiceProvider {

    /**
     * send push notification
     * 
     * send push notification based on role type
     * @param type $data 
     * @return type
     */
    public static function sendPushNotification($device, $message, $params = false) {
        if (strtolower($device->device_type) == Device::DEVICE_TYPE_IOS) {
                static::sendPushIOS($device->device_token, $message, $params);
            } else if (strtolower($device->device_type) == Device::DEVICE_TYPE_ANDROID) {
                static::sendPushAndroid($device->device_token, $message, $params);
            }
    }

    public static function sendPushIOS($device_identifier, $message, $params = false) {
        if (!$device_identifier || strlen($device_identifier) < 22) {
            return;
        }
        
        if (env('APP_ENV') == 'local') {
            $config = config('pushnotification.apple.sandbox');
            $certFile = $config['pem_file'];
            $url = $config['url'];
            $passphrase = $config['passphrase'];
        } else {
            $config = config('pushnotification.apple.production');
            $certFile = $config['pem_file'];
            $url = $config['url'];
            $passphrase = $config['passphrase'];
        }

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certFile);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client(
                $url, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            return false;
        }

        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'AlertSound.mp3'
        );

        if (!empty($params['data'])) {
            //$body['data'] = $params['data'];
            $body['data'] = $params;
        }

        // Encode the payload as JSON
        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_identifier) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        
        if (!$result) {
          echo 'Message not delivered' . PHP_EOL;
        } else {
          echo 'Message successfully delivered' . PHP_EOL;
        }

        fclose($fp);
    }

    public static function sendPushAndroid($device_token, $message, $params = false) {
        if (!$device_token) {
            return;
        }

        $config = config('pushnotification.android');
        $fields = array
            (
            'data'=>$params,
            'to' => $device_token
        );

        $headers = array
            (
            'Authorization: key=' . $config['server_key'],
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $config['url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_exec($ch);
        curl_close($ch);
    }

    
    /**
     * To get unread count
     * 
     * It will return unread count
     * @param array $data
     * @return object response
     */
    public static function getUnreadCount() {
        try {
            $user = Auth::user();
            $unReadCount = Notification::join('users as sender', 'notification.sender_id', '=', 'sender.user_id')
                            ->where('notification.receiver_id', '=', $user->user_id)
                            ->whereNull('notification.read_at')->get()->count();
            if ($unReadCount) {
                static::$data['data']['unReadCount'] = $unReadCount;
                static::$data['success'] = true;
                static::$data['message'] = trans('messages.record_fetched');
            } else {
                static::$data['data']['unReadCount'] = 0;
                static::$data['success'] = true;
                static::$data['message'] = trans('messages.record_fetched');
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return static::responseError(trans('messages.exception_msg'));
        }
        return static::baseServiceResponse();
    }

    /**
     * Return motification list of seeker/provider
     * @param type $page
     * @param type $userId
     */
    public static function serviceNotficationList($data) {
        try {
            $start = (int) isset($data['start']) ? $data['start'] : 0;
            $limit = (int) isset($data['limit']) ? $data['limit'] : config('marketplace.defaul_product_per_page');
            $user = Auth::user();

            $notification_list = Notification::userNotificationList($start, $limit);

            if (count($notification_list)) {
                Notification::where('receiver_id', '=', $user->user_id)->whereNull('read_at')->update(['read_at' => date("Y-m-d H:i:s")]);
                
                static::$data['data']['list'] = $notification_list['list'];
                static::$data['data']['unReadCount'] = 0;
                static::$data['data']['total'] = $notification_list['total'];
                static::$data['data']['start'] = $start;
                static::$data['data']['limit'] = $limit;
                static::$data['success'] = true;
                static::$data['message'] = trans('messages.record_fetched');
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return static::responseError(trans('messages.exception_msg'));
        }
        return static::baseServiceResponse();
    }

    /**
     * To mark a chat as read
     * 
     * It will mark a chat as read
     * @param array $data
     * @return object response
     */
    public static function markRead($data) {
        try {
            $user = Auth::user();
            $notification = Notification::find($data['notificationId']);
            if ($notification && ($notification->receiver_id == $user->user_id)) {
                $notification->read_at =  date('Y-m-d H:i:s');
                $notification->save();
                return static::responseSuccess(null, trans('messages.record_updated'));
            } else {
                return static::responseError(trans('messages.record_not_exist'), Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return static::responseError(trans('messages.exception_msg'));
        }
    }
    
    public static function notificationFromAdmin(AppMessage $appMessage){
        $message = $appMessage->message;
        $user = User::getAdminUserDetailsForNotification();
        
        if($appMessage->messageTo==1) {
            static::getAppRecruiterNotification($user, $message, 2);
            static::getAppDeviceNotification($user, $message, 3);
        }
        else if($appMessage->messageTo==2) {
            static::getAppRecruiterNotification($user, $message, $appMessage->messageTo);
            
        } else if($appMessage->messageTo==3) {
            static::getAppDeviceNotification($user, $message, $appMessage->messageTo);
        }
        
    }
    
    public static function getAppRecruiterNotification($user, $message, $groupId)
    {
        $insertData = [];
        $devices = User::getAllUserByRole($groupId);
        if(!empty($devices)) {
            foreach ($devices as $deviceData){
                $data = ['image'=> url('web/images/dentaMatchLogo.png'), 'message' => $message];
                $insertData[] = ['receiver_id'=>$deviceData->id,
                                'sender_id'=>$user->id,
                                'notification_data'=> json_encode($data),
                                'created_at'=>date('Y-m-d h:i:s'),
                                'notification_type' => Notification::OTHER,
                                ];
            }

            if(!empty($insertData)){
                Notification::insert($insertData);
            }
        }
    }
    
    public static function getAppDeviceNotification($user, $message, $groupId)
    {
        $params['data'] = [
                            'notificationData' => $message,
                            'notification_title'=>'App Admin Update',
                            'sender_id' => $user->id,
                            'type' => 1,
                            'notificationType' => Notification::OTHER,
                        ];
        
        $devices = Device::getAllDeviceToken($groupId);
        if(!empty($devices)) {

            $insertData = [];
            if(!empty($devices)) {
                foreach ($devices as $deviceData){
                    if ($deviceData->device_token && strlen($deviceData->device_token) >= 22) {
                        $insertData[] = ['receiver_id'=>$deviceData->user_id,
                            'sender_id'=>$user->id,
                            'notification_data'=>$message,
                            'created_at'=>date('Y-m-d h:i:s'),
                            'notification_type' => Notification::OTHER,
                            ];
                    }
                    static::sendPushNotification($deviceData, $message, $params);
                }
            }
            if(!empty($insertData)){
                Notification::insert($insertData);
            }
        }
    }

}
