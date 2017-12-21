<?php
namespace App\Helpers;
use App\Models\UserChat;

class UserChatHelper {

    public static function recruiterChatCount($userId) {
        $recruiterChatCount = UserChat::getChatCountsForRecruiter($userId);
        return $recruiterChatCount;
    }
}

