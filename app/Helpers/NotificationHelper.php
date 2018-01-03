<?php
namespace App\Helpers;
use App\Models\Notification;

class NotificationHelper {

    public static function topNotificationList($userId) {
        $notificationModel = Notification::userTopNotification($userId);
        return $notificationModel;
    }
    
    public static function NotificationAdmin($userId){
        $notificationModel = Notification::NotificationAdmin($userId);
        return $notificationModel;
    }
}

