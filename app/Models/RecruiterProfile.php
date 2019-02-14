<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\RecruiterProfile
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $is_subscribed
 * @property string|null $stripe_token
 * @property string|null $customer_id
 * @property int $accept_term
 * @property int|null $free_period
 * @property int|null $auto_renewal
 * @property string|null $validity
 * @property string|null $office_name
 * @property string|null $office_desc
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $recruiter
 *
 * @method static Builder|RecruiterProfile newModelQuery()
 * @method static Builder|RecruiterProfile newQuery()
 * @method static Builder|RecruiterProfile query()
 * @method static Builder|RecruiterProfile whereAcceptTerm($value)
 * @method static Builder|RecruiterProfile whereAutoRenewal($value)
 * @method static Builder|RecruiterProfile whereCreatedAt($value)
 * @method static Builder|RecruiterProfile whereCustomerId($value)
 * @method static Builder|RecruiterProfile whereFreePeriod($value)
 * @method static Builder|RecruiterProfile whereId($value)
 * @method static Builder|RecruiterProfile whereIsSubscribed($value)
 * @method static Builder|RecruiterProfile whereOfficeDesc($value)
 * @method static Builder|RecruiterProfile whereOfficeName($value)
 * @method static Builder|RecruiterProfile whereStripeToken($value)
 * @method static Builder|RecruiterProfile whereUpdatedAt($value)
 * @method static Builder|RecruiterProfile whereUserId($value)
 * @method static Builder|RecruiterProfile whereValidity($value)
 * @mixin \Eloquent
 */
class RecruiterProfile extends Model
{
    protected $table = 'recruiter_profiles';

    protected $fillable = ['user_id', 'is_subscribed', 'accept_term', 'free_period', 'auto_renewal', 'validity', 'office_name', 'office_desc'];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function updateOfficeDetail($request)
    {
        return RecruiterProfile::where('user_id', Auth::user()->id)->update([
            'office_name' => $request->officeName,
            'office_desc' => $request->officeDescription
        ]);
    }

    public static function updateStripeToken($token)
    {
        return RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['stripe_token' => $token]);
    }

    public static function updateCustomerId($customerId)
    {
        return RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['customer_id' => $customerId]);
    }

}
