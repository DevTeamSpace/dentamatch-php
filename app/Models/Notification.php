<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Notification extends Model {

    protected $table = 'notification_logs';
    protected $primaryKey = 'id';
    protected $hidden = ['updated_at', 'deleted_at'];
    
    protected $fillable = ['sender_id', 'receiver_id', 'job_list_id', 'notification_data', 'notification_type'];
    
    const LIMIT = 10;
    
    const ACCEPTJOB = 1;
    const HIRED = 2;
    const JOBCANCEL = 3;
    const DELETEJOB = 4;
    const VERIFYDOCUMENT = 5;
    const COMPLETEPROFILE = 6;
    const CHATMESSAGE = 7;
    const OTHER = 8;
    const INVITED = 9;
    const JOBSEEKERAPPLIED = 10;
    const JOBSEEKERACCEPTED = 11;
    const JOBSEEKERREJECTED = 12;
    
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
