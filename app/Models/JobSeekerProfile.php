<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class JobSeekerProfile extends Model {

    use Eloquence,
        Mappable;
//    use SoftDeletes;

    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'first_name', 'last_name', 'profile_pic', 'zipcode', 'latitude', 'longitude', 'preferred_job_location', 'job_titile_id', 'dental_state_board', 'license_number', 'state', 'about_me', 'verification_code', 'is_verified', 'is_completed', 'is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday', 'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday'];

}
