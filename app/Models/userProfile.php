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
        $s3Url = env('AWS_URL');
        $s3Bucket = env('AWS_BUCKET');
        
        $userModel = static::select('id', 'user_id', 'first_name', 'last_name', 
                        'profile_pic', 'dental_state_board', 'license_number', 'state', 'about_me')
                    ->where('is_completed', 1)
                    ->where('user_id', $userId)
                    ->first();
        
        if($userModel) {
            $return = $userModel->toArray();
            $profilePic = $return['profile_pic'];
            $dentalStateBoard = $return['dental_state_board'];
            $return['profile_pic'] = !empty($profilePic) ? $s3Url.DIRECTORY_SEPARATOR.$s3Bucket.$profilePic : $profilePic;
            $return['dental_state_board'] = !empty($dentalStateBoard) ? $s3Url.DIRECTORY_SEPARATOR.$s3Bucket.$dentalStateBoard : $dentalStateBoard;
        }
        return $return;
    }

}
