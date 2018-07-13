<?php
namespace App\Helpers;
use App\Models\Notification;

class NotificationHelper {

    /**
     * Return list of top 3 notification 
     * @param  userId
     */
    public static function topNotificationList($userId) {
        $notificationModel = Notification::userTopNotification($userId);
        return $notificationModel;
    }
    
    /**
     * Return last unread notification sent by admin
     * @param  userId
     */
    public static function notificationAdmin($userId){
        $notificationModel = Notification::notificationAdmin($userId);
        return $notificationModel;
    }
}

