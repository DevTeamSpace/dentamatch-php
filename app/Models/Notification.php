<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Notification extends Model {

    protected $table = 'notification_logs';
    protected $primaryKey = 'id';
    protected $hidden = ['updated_at', 'deleted_at'];
    
    const LIMIT = 10;
    
    const INVITED = 1;
    const APPLIED = 2;
    const SHORTLISTED = 3;
    const HIRED = 4;
    const REJECTED = 5;
    const CANCELLED = 6;
    
    public static function userNotificationList($reqData)
    {
        $array = array("list" => [], "total" => 0);
        $query = Notification::where('receiver_id', '=', $reqData['userId'])->orderBy('id', 'DESC');
        $total = $query->count();
        $page = $reqData['page'];
        $limit = SavedJobs::LIMIT;
        $skip = 0;
        if ($page > 1) {
            $skip = ($page - 1) * $limit;
        }
        $list = $query->skip($skip)->take($limit)->get();
        $array['list'] = $list;
        $array['total'] = $total;
        return $array;    
    }
    
    public static function createNotification($data)
    {
        static::insert($data);
    }
}
