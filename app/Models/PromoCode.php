<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


/**
 * App\Models\PromoCode
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $code
 * @property string $name
 * @property string $valid_days_from_sign_up
 * @property string $valid_until
 * @property int $free_days
 * @property int $discount_on_subscription
 * @property int $active
 * @property string $subscription
 * @method static Builder|PromoCode newModelQuery()
 * @method static Builder|PromoCode newQuery()
 * @method static Builder|PromoCode query()
 * @method static Builder|PromoCode whereActive($value)
 * @method static Builder|PromoCode whereCode($value)
 * @method static Builder|PromoCode whereCreatedAt($value)
 * @method static Builder|PromoCode whereDiscountOnSubscription($value)
 * @method static Builder|PromoCode whereFreeDays($value)
 * @method static Builder|PromoCode whereId($value)
 * @method static Builder|PromoCode whereName($value)
 * @method static Builder|PromoCode whereSubscription($value)
 * @method static Builder|PromoCode whereUpdatedAt($value)
 * @method static Builder|PromoCode whereValidDaysFromSignUp($value)
 * @method static Builder|PromoCode whereValidUntil($value)
 * @mixin \Eloquent
 */
class PromoCode extends Model
{

}
