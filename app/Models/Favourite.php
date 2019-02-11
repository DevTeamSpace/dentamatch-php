<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Favourite
 *
 * @property int $id
 * @property int $recruiter_id
 * @property int $seeker_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @property-read User $recruiter
 * @property-read User $seeker
 *
 * @method static bool|null forceDelete()
 * @method static Builder|Favourite newModelQuery()
 * @method static Builder|Favourite newQuery()
 * @method static QueryBuilder|Favourite onlyTrashed()
 * @method static Builder|Favourite query()
 * @method static bool|null restore()
 * @method static Builder|Favourite whereCreatedAt($value)
 * @method static Builder|Favourite whereDeletedAt($value)
 * @method static Builder|Favourite whereId($value)
 * @method static Builder|Favourite whereRecruiterId($value)
 * @method static Builder|Favourite whereSeekerId($value)
 * @method static Builder|Favourite whereUpdatedAt($value)
 * @method static QueryBuilder|Favourite withTrashed()
 * @method static QueryBuilder|Favourite withoutTrashed()
 * @mixin \Eloquent
 */
class Favourite extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;

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
