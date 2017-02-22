<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table        = 'users';
    protected $primaryKey   = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verification_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function userGroup(){
        return $this->hasOne(UserGroup::class);
    }
    
    public static function validateRecuriterEmail($email){
        return static::join('user_groups','user_groups.user_id','=','users.id')
            ->where('user_groups.group_id',  UserGroup::RECRUITER)    
            ->where('users.email',$email)
            ->count();
    }
    
    public static function getAdminUserDetailsForNotification() {
        return static::where('is_active',1)->where('group_id',1)
                ->join('user_groups','users.id','=','user_groups.user_id')->first();
    }
}
