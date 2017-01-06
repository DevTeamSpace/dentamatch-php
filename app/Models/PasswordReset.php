<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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