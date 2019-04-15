<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Favourite
 *
 * @property int $id
 * @property int $recruiter_id
 * @property int $seeker_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at     SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @property-read string|null $mapping_for
 * @property-read User $recruiter
 * @property-read User $seeker
 *
 * @method static Builder|Favourite newModelQuery()
 * @method static Builder|Favourite newQuery()
 * @method static Builder|Favourite query()
 * @method static Builder|Favourite whereCreatedAt($value)
 * @method static Builder|Favourite whereDeletedAt($value)
 * @method static Builder|Favourite whereId($value)
 * @method static Builder|Favourite whereRecruiterId($value)
 * @method static Builder|Favourite whereSeekerId($value)
 * @method static Builder|Favourite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Favourite extends Model
{
    use Eloquence, Mappable;

    protected $maps = [
        'recruiterId'     => 'message_to',
        'seekerId'        => 'message_sent',
        'cronMessageSent' => 'cron_message_sent'
    ];
    protected $hidden = ['id', 'recruiter_id', 'seeker_id', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [];
    protected $appends = ['recruiterId', 'seekerId'];
    protected $dates = ['deleted_at'];

    public function recruiter()
    {
        return $this->belongsTo(User::class);
    }

    public function seeker()
    {
        return $this->belongsTo(User::class);
    }

}
