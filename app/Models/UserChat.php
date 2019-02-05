<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserChat
 *
 * @property int $id
 * @property int $from_id
 * @property int $to_id
 * @property string $message
 * @property int $read_status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereFromId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserChat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserChat extends Model
{
    const READ = 1;
    const UNREAD = 0;
    
    protected $table = 'user_chat';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'fromId' => 'from_id',
        'toId' => 'to_id',
        'readStatus' => 'read_status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        ];  
    
    public static function getChatCountsForRecruiter($recruiterId) {
        // todo why using get?
        return static::where('to_id', $recruiterId)->where('read_status',self::UNREAD)->get()->count();
    }
   
}