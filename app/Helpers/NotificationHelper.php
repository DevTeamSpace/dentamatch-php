<?php
namespace App\Helpers;
use App\Models\Notification;

class NotificationHelper {

    public static function topNotificationList($userId) {
        $notificationModel = Notification::userTopNotification($userId);
        return $notificationModel;
    }
    
    public static function notificationAdmin($userId){
        $notificationModel = Notification::notificationAdmin($userId);
        return $notificationModel;
    }
}

