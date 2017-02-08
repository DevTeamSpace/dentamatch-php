<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRatings extends Model
{
    protected $table = 'job_ratings';
    protected $primaryKey = 'id';
    
    protected $hidden       = ['created_at','updated_at'];
}