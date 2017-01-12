<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSeekerSkills extends Model
{
    //
    protected $table = 'jobseeker_skills';
    protected $primaryKey = 'id';
    
    
    protected $hidden       = ['deleted_at','created_at','updated_at'];
}

