<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereAcceptTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereAutoRenewal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereFreePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereIsSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereOfficeDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereOfficeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereStripeToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RecruiterProfile whereValidity($value)
 * @mixin \Eloquent
 */
class RecruiterProfile extends Model {

    protected $table = 'recruiter_profiles';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'is_subscribed', 'accept_term', 'free_period', 'auto_renewal', 'validity', 'office_name', 'office_desc'];

    public static function updateOfficeDetail($request) {
        return RecruiterProfile::where('user_id', Auth::user()->id)->update([
                    'office_name' => $request->officeName,
                    'office_desc' => $request->officeDescription
        ]);
    }
    
    public static function updateStripeToken($token){
        return RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['stripe_token' => $token]);
    }
    
    public static function updateCustomerId($customerId){
        return RecruiterProfile::where(['user_id' => Auth::user()->id])->update(['customer_id' => $customerId]);
    }

}
