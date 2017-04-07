<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class JobseekerTempHired extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_temp_hired';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    
    protected $hidden = [
       'updated_at', 'deleted_at'
    ];
    
    
}
