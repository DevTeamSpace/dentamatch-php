<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTitles extends Model
{
    //
    protected $table = 'job_titles';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'JobtitleName' => 'jobtitle_name',
        ];
    protected $hidden       = ['is_active','created_at','updated_at'];
}