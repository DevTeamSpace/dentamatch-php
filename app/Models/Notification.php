<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Notification
 *
 * @property int $id
 * @property int|null $sender_id
 * @property int $receiver_id
 * @property int|null $job_list_id
 * @property string $notification_data
 * @property int $seen
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property int $notification_type
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereDeletedAt($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereJobListId($value)
 * @method static Builder|Notification whereNotificationData($value)
 * @method static Builder|Notification whereNotificationType($value)
 * @method static Builder|Notification whereReceiverId($value)
 * @method static Builder|Notification whereSeen($value)
 * @method static Builder|Notification whereSenderId($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Notification extends Model
{

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
    const JOBSEEKERCANCELLED = 13;
    const REJECTED = 14;
    const LICENSEACCEPTREJECT = 15;

    public static function userNotificationList($reqData)
    {
        $array = ["list" => [], "total" => 0];
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

    /**
     * Return list of top 3 notifications
     * @param  int userId
     * @return array
     */
    public static function getUserTopNotifications($userId)
    {
        $return = ['data' => [], 'total' => '0'];
        $query = static::where('receiver_id', $userId)
            ->where('seen', 0)
            ->orderBy('created_at', 'DESC');

        $total = $query->count('receiver_id');
        $return['total'] = $total;
        $data = $query->take(3)->get();
        if ($data) {
            $return['data'] = $data;
        }
        return $return;
    }

    /**
     * Return last unread notification sent by admin
     * @param  int userId
     * @return Notification|Model|null
     */
    public static function getLastNotificationFromAdmin($userId)
    {
        return Notification::where('sender_id', 1)
            ->where('receiver_id', $userId)->where('seen', 0)->orderBy('id', 'desc')->first();
    }
}
