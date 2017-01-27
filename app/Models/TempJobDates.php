<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempJobDates extends Model
{   
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
