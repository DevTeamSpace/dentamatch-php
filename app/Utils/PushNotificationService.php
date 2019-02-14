<?php

namespace App\Utils;

use App\Models\Device;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

/**
 *  PushNotificationService class contains methods for sending push notification to devices
 */
class PushNotificationService
{
    /**
     * Send push notification
     * @param Device $device
     * @param string $message
     * @param $params
     * @param null $userId
     * @return bool
     */
    public static function send($device, $message, $params = false, $userId = null)
    {
        $deviceToken = $device->device_token;
        if (!$deviceToken)
            return false;

        $badgeCount = 0;
        if ($userId != null) {
            $badgeCount = self::getUnreadNotificationCount($userId);
        }

        switch (mb_strtolower($device->device_type)) {
            case Device::DEVICE_TYPE_IOS:
                return static::sendPushIOS($deviceToken, $message, $params, $badgeCount);

            case Device::DEVICE_TYPE_ANDROID:
                return static::sendPushAndroid($deviceToken, $params, $badgeCount);
        }

        return false;
    }

    /**
     * @param $device_identifier
     * @param $message
     * @param bool $params
     * @param int $badgeCount
     * @return bool
     */
    private static function sendPushIOS($device_identifier, $message, $params = false, $badgeCount = 0)
    {
        try {
            if (strlen($device_identifier) < 22)
                return false;

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

            $body['aps'] = [
                'alert' => $message,
                'badge' => $badgeCount,
                'sound' => 'AlertSound.mp3'
            ];

            if (!empty($params['data'])) {
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

            return $result;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
        }

        return false;
    }

    /**
     * @param $device_token
     * @param bool $params
     * @param int $badgeCount
     * @return bool
     */
    private static function sendPushAndroid($device_token, $params = false, $badgeCount = 0)
    {
        try {
            $config = config('pushnotification.android');
            $fields = [
                'data'  => $params,
                'badge' => $badgeCount,
                'to'    => $device_token
            ];

            $headers = [
                'Authorization: key=' . $config['server_key'],
                'Content-Type: application/json'
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $config['url']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            curl_exec($ch);
            curl_close($ch);

            return true;
        } catch (\Exception $e) {
            Log::error(__METHOD__ . ' ' . $e->getMessage());
        }

        return false;
    }

    /**
     * @param $userId
     * @return int
     */
    public static function getUnreadNotificationCount($userId)
    {
        return Notification::where('receiver_id', $userId)->where('seen', 0)->count();
    }

}
