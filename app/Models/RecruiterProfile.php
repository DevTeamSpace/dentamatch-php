<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;

/**
 * App\Models\RecruiterProfile
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $is_subscribed
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property int|null $trial_ends_at
 * @property int $accept_term
 * @property string|null $office_name
 * @property string|null $office_desc
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $recruiter
 * @property Subscription[]|Collection $subscriptions
 * @property RecruiterOffice[]|Collection $offices
 *
 * @method static Builder|RecruiterProfile newModelQuery()
 * @method static Builder|RecruiterProfile newQuery()
 * @method static Builder|RecruiterProfile query()
 * @method static Builder|RecruiterProfile whereAcceptTerm($value)
 * @method static Builder|RecruiterProfile whereCreatedAt($value)
 * @method static Builder|RecruiterProfile whereStripeId($value)
 * @method static Builder|RecruiterProfile whereId($value)
 * @method static Builder|RecruiterProfile whereIsSubscribed($value)
 * @method static Builder|RecruiterProfile whereOfficeDesc($value)
 * @method static Builder|RecruiterProfile whereOfficeName($value)
 * @method static Builder|RecruiterProfile whereUpdatedAt($value)
 * @method static Builder|RecruiterProfile whereUserId($value)
 * @mixin \Eloquent
 */
class RecruiterProfile extends Model
{
    use Billable;

    protected $table = 'recruiter_profiles';

    protected $fillable = ['user_id', 'is_subscribed', 'accept_term', 'office_name', 'office_desc'];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function offices()
    {
        return $this->hasMany(RecruiterOffice::class, 'user_id', 'user_id');
    }

    public static function updateOfficeDetail($request)
    {
        return RecruiterProfile::where('user_id', Auth::user()->id)->update([
            'office_name' => $request->officeName,
            'office_desc' => $request->officeDescription
        ]);
    }

    /**
     * @return RecruiterProfile
     */
    public static function current()
    {
        return self::where(['user_id' => Auth::user()->id])->first();
    }

}
