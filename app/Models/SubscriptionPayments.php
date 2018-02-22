<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

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
        ];
    protected $hidden       = ['created_at','updated_at'];
    protected $fillable     = ['recruiterId','expiryDate','paymentId','paymentResponse'];
    protected $appends      = ['recruiterId','expiryDate','paymentId','paymentResponse'];
}
