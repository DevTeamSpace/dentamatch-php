<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterProfile extends Model {

    protected $table = 'recruiter_profiles';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'is_subscribed', 'accept_term', 'free_period', 'auto_renewal', 'validity', 'office_name', 'office_desc'];

}
