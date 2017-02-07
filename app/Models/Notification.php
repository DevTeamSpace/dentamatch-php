<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Notification extends Model {

    protected $table = 'notification_logs';
    protected $primaryKey = 'id';
    protected $hidden = ['updated_at', 'deleted_at'];
    
    public static function userNotificationList($start = 0, $limit = '')
    {
        $array = array("list" => [], "total" => 0);
        $user = Auth::user();
        $query = static::join('users as sender', 'notification.sender_id', '=', 'sender.user_id')
                    ->where('notification.receiver_id', '=', $user->user_id)
                    ->select('notification.*', DB::raw('if(notification.read_at IS NULL,"", notification.read_at) as readAt'), 'sender.name as senderName', 'sender.user_id as senderUserId')
                    ->orderBy('notification.notification_id', 'DESC');
        $total = $query->count();
        if ($limit)
            $query->skip($start)->take($limit);
        
            $list = $query->get();
            $array['list'] = $list;
            $array['total'] = $total;
            return $array;
    }
}
