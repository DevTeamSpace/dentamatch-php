<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerCertificates extends Model
{
    //
    protected $table = 'jobseeker_certificates';
    protected $primaryKey = 'id';
    
    protected $guarded = array('id');
    protected $hidden       = ['created_at','updated_at','deleted_at'];
}
