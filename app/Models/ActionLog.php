<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\ActionLog
 *
 * @property int $id
 * @property int $category
 * @property int $type
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ActionLog newModelQuery()
 * @method static Builder|ActionLog newQuery()
 * @method static Builder|ActionLog query()
 * @method static Builder|ActionLog whereCategory($value)
 * @method static Builder|ActionLog whereCreatedAt($value)
 * @method static Builder|ActionLog whereId($value)
 * @method static Builder|ActionLog whereType($value)
 * @method static Builder|ActionLog whereUpdatedAt($value)
 * @method static Builder|ActionLog whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $request_data
 * @property string|null $response_data
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActionLog whereRequestData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActionLog whereResponseData($value)
 * @property int|null $job_id
 * @property int|null $job_list_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActionLog whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActionLog whereJobListId($value)
 * @property int|null $to_user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ActionLog whereToUserId($value)
 */
class ActionLog extends Model
{
    protected $table = 'action_log';

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function job() {
        return $this->belongsTo(RecruiterJobs::class, 'job_id');
    }
}
