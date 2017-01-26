<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobLists extends Model
{
    
    const INVITED = 1;
    const APPLIED = 2;
    const SHORTLISTED = 3;
    const HIRED = 4;
    const REJECTED = 5;
    const CANCELLED = 6;
    
    protected $table = 'job_lists';
    protected $primaryKey = 'id';
    
    
}