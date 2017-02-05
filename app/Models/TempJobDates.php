<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

class TempJobDates extends Model
{   
    use Eloquence, Mappable;
    
    protected $table = 'temp_job_dates';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $maps          = [
        'recruiterJobId' => 'recruiter_job_id',
        'jobDate' => 'job_date',
        ];
    protected $hidden       = ['created_at','updated_at'];
    protected $fillable     = ['recruiterJobId','jobDate'];
    protected $appends      = ['recruiterJobId','jobDate'];
}
