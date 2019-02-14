<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserChat
 *
 * @property int $id
 * @property int $from_id
 * @property int $to_id
 * @property string $message
 * @property int $read_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $from
 * @property-read User $to
 * @method static Builder|UserChat newModelQuery()
 * @method static Builder|UserChat newQuery()
 * @method static Builder|UserChat query()
 * @method static Builder|UserChat whereCreatedAt($value)
 * @method static Builder|UserChat whereFromId($value)
 * @method static Builder|UserChat whereId($value)
 * @method static Builder|UserChat whereMessage($value)
 * @method static Builder|UserChat whereReadStatus($value)
 * @method static Builder|UserChat whereToId($value)
 * @method static Builder|UserChat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserChat extends Model
{
    const READ = 1;
    const UNREAD = 0;

    protected $table = 'user_chat';
    protected $primaryKey = 'id';

    protected $maps = [
        'fromId'     => 'from_id',
        'toId'       => 'to_id',
        'readStatus' => 'read_status',
        'createdAt'  => 'created_at',
        'updatedAt'  => 'updated_at',
    ];

    public function from()
    {
        return $this->belongsTo(User::class);
    }

    public function to()
    {
        return $this->belongsTo(User::class);
    }

    public static function getChatCountsForRecruiter($recruiterId)
    {
        // todo why using get?
        return static::where('to_id', $recruiterId)->where('read_status', self::UNREAD)->get()->count();
    }

}