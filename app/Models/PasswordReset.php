<?php

namespace App\Models;

/**
 * App\Models\PasswordReset
 *
 * @property int|null $user_id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereUserId($value)
 * @mixin \Eloquent
 */
class PasswordReset extends \Eloquent  {
    protected $table        = 'password_resets';

    protected $maps          = ['id'=>'user_id'];

    protected $hidden       = ['user_id'];
    protected $fillable     = ['email','token','user_id'];
    protected $appends      = [];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function user() {
        return $this->hasOne('App\Models\User','id');
    }
    
    public static function getLatestUserTokenDetails($token){
        return static::where(['token' => $token])->orderBy('created_at','desc')->first();
    }

}