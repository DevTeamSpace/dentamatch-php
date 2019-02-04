<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Models\SubscriptionPayments
 *
 * @property int $id
 * @property int $recruiter_id
 * @property float $amount
 * @property string $payment_id
 * @property string $subscription_expiry_date
 * @property string $payment_response
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $trial_end
 * @property-read string|null $mapping_for
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments wherePaymentResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereRecruiterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereSubscriptionExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereTrialEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SubscriptionPayments whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubscriptionPayments extends Model
{
    use Eloquence, Mappable;
    
    protected $table = 'subscription_payments';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $maps          = [
        'recruiterId' => 'recruiter_id',
        'expiryDate' => 'subscription_expiry_date',
        'paymentId' => 'payment_id',
        'paymentResponse' => 'payment_response',
        'trialEnd' => 'trial_end',
        ];
    protected $hidden       = ['created_at','updated_at'];
    protected $fillable     = ['recruiterId','expiryDate','paymentId','paymentResponse','trialEnd'];
    protected $appends      = ['recruiterId','expiryDate','paymentId','paymentResponse','trialEnd'];
}
