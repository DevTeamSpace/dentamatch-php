<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use App\Model\Device;
use App\Model\Notification;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Model\User;

/**
 * NotificationServiceProvider class contains methods for notification management
 */
class NotificationServiceProvider extends BaseServiceProvider {

    /**
     * send push notification
     * 
     * send push notification based on role type
     * @param type $data 
     * @return type
     */
    public static function sendPushNotification($devices, $message, $params = false) {
        if ($device->device_type == AppUserToken::DEVICE_TYPE_IOS) {
                static::sendPushIOS($device->device_token, $message, $params);
            } else if ($device->device_type == AppUserToken::DEVICE_TYPE_ANDROID) {
                static::sendPushAndroid($device->device_token, $message, $params);
            }
    }

    public static function sendPushIOS($device_identifier, $message, $params = false) {
        if (!$device_identifier || strlen($device_identifier) < 22) {
            return;
        }
        
        if (env('APP_ENV') == 'local') {
            $certFile = public_path('notification_pems/push_development.pem');
            $url = 'ssl://gateway.sandbox.push.apple.com:2195';
        } else {
            $certFile = public_path('notification_pems/push_distribution.pem');
            $url = 'ssl://gateway.push.apple.com:2195';
        }

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $certFile);
        stream_context_set_option($ctx, 'ssl', 'passphrase', '');

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
            $body['data'] = $params['data'];
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

        $config = config('push_notification.android');

        $notification = ['text' => $message];
        $body = json_encode($params);

        $fields = array
            (
            'notification' => $notification,
            'data'=>$body,
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
                self::$data['data']['unReadCount'] = $unReadCount;
                self::$data['success'] = true;
                self::$data['message'] = trans('messages.record_fetched');
            } else {
                self::$data['data']['unReadCount'] = 0;
                self::$data['success'] = true;
                self::$data['message'] = trans('messages.record_fetched');
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return static::responseError(trans('messages.exception_msg'));
        }
        return self::baseServiceResponse();
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
                
                self::$data['data']['list'] = $notification_list['list'];
                self::$data['data']['unReadCount'] = 0;
                self::$data['data']['total'] = $notification_list['total'];
                self::$data['data']['start'] = $start;
                self::$data['data']['limit'] = $limit;
                self::$data['success'] = true;
                self::$data['message'] = trans('messages.record_fetched');
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
            return static::responseError(trans('messages.exception_msg'));
        }
        return self::baseServiceResponse();
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
                $notification->read_at = $current_time = date('Y-m-d H:i:s');
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

}