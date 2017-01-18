<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserProfile extends Model {

    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public static function getUserProfile($userId)
    {
        $return = [];
        $userModel = static::select('id', 'user_id', 'first_name', 'last_name', 
                        'profile_pic', 'dental_state_board', 'license_number', 'state', 'about_me')
                    ->where('user_id', $userId)
                    ->first();
        
        if($userModel) {
            $return = $userModel->toArray();
        }
        return $return;
    }

}
