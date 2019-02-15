<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\PasswordReset
 *
 * @property int|null $user_id
 * @property string $email
 * @property string $token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $user
 * @method static Builder|PasswordReset newModelQuery()
 * @method static Builder|PasswordReset newQuery()
 * @method static Builder|PasswordReset query()
 * @method static Builder|PasswordReset whereCreatedAt($value)
 * @method static Builder|PasswordReset whereDeletedAt($value)
 * @method static Builder|PasswordReset whereEmail($value)
 * @method static Builder|PasswordReset whereToken($value)
 * @method static Builder|PasswordReset whereUpdatedAt($value)
 * @method static Builder|PasswordReset whereUserId($value)
 * @mixin \Eloquent
 */
class PasswordReset extends \Eloquent
{
    protected $table = 'password_resets';

    protected $maps = ['id' => 'user_id'];

    protected $hidden = ['user_id'];
    protected $fillable = ['email', 'token', 'user_id'];
    protected $appends = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->hasOne(User::class, 'id');
    }

    public static function getLatestUserTokenDetails($token)
    {
        return static::where(['token' => $token])->orderBy('created_at', 'desc')->first();
    }

}