<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model {

    protected $table = 'jobseeker_work_experiences';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'job_title_id', 'months_of_expereince', 'office_name', 'office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email'];

}
