<?php
namespace App\Helpers;
use App\Models\UserChat;

class UserChatHelper {

    /**
     * Return unread chat message count
     * @param  userId
     */
    public static function recruiterChatCount($userId) {
        $recruiterChatCount = UserChat::getChatCountsForRecruiter($userId);
        return $recruiterChatCount;
    }
}

