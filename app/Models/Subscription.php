<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Subscription
 *
 * @property int $id
 * @property int $recruiter_id
 * @property float $amount
 * @property string $subscription_id
 * @property string $subscription_expiry_date
 * @property string $subscription_response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $trial_end
 * @property bool $cancel_at_period_end
 * @property-read User $recruiter
 *
 * @method static Builder|Subscription newModelQuery()
 * @method static Builder|Subscription newQuery()
 * @method static Builder|Subscription query()
 * @method static Builder|Subscription whereCreatedAt($value)
 * @method static Builder|Subscription whereId($value)
 * @method static Builder|Subscription whereSubscriptionId($value)
 * @method static Builder|Subscription whereSubscriptionResponse($value)
 * @method static Builder|Subscription whereRecruiterId($value)
 * @method static Builder|Subscription whereSubscriptionExpiryDate($value)
 * @method static Builder|Subscription whereTrialEnd($value)
 * @method static Builder|Subscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Subscription extends Model
{
    use Eloquence;

    protected $table = 'subscriptions';
    protected $primaryKey = 'id';

    protected $fillable = ['recruiter_id', 'subscription_expiry_date', 'subscription_id', 'trial_end', 'subscription_response', 'cancel_at_period_end'];

    public function recruiter()
    {
        return $this->belongsTo(User::class);
    }
}
