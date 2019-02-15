<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\SubscriptionPayments
 *
 * @property int $id
 * @property int $recruiter_id
 * @property float $amount
 * @property string $payment_id
 * @property string $subscription_expiry_date
 * @property string $payment_response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $trial_end
 * @property-read string|null $mapping_for
 * @property-read User $recruiter
 *
 * @method static Builder|SubscriptionPayments newModelQuery()
 * @method static Builder|SubscriptionPayments newQuery()
 * @method static Builder|SubscriptionPayments query()
 * @method static Builder|SubscriptionPayments whereAmount($value)
 * @method static Builder|SubscriptionPayments whereCreatedAt($value)
 * @method static Builder|SubscriptionPayments whereId($value)
 * @method static Builder|SubscriptionPayments wherePaymentId($value)
 * @method static Builder|SubscriptionPayments wherePaymentResponse($value)
 * @method static Builder|SubscriptionPayments whereRecruiterId($value)
 * @method static Builder|SubscriptionPayments whereSubscriptionExpiryDate($value)
 * @method static Builder|SubscriptionPayments whereTrialEnd($value)
 * @method static Builder|SubscriptionPayments whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubscriptionPayments extends Model
{
    use Eloquence, Mappable;

    protected $table = 'subscription_payments';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    protected $maps = [
        'recruiterId'     => 'recruiter_id',
        'expiryDate'      => 'subscription_expiry_date',
        'paymentId'       => 'payment_id',
        'paymentResponse' => 'payment_response',
        'trialEnd'        => 'trial_end',
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['recruiterId', 'expiryDate', 'paymentId', 'paymentResponse', 'trialEnd'];
    protected $appends = ['recruiterId', 'expiryDate', 'paymentId', 'paymentResponse', 'trialEnd'];

    public function recruiter()
    {
        return $this->belongsTo(User::class);
    }
}
