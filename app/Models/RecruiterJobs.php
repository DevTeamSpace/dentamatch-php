<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecruiterJobs extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    const FULLTIME = 1;
    const PARTTIME = 2;
    const TEMPORARY = 3;
    
    protected $table = 'recruiter_jobs';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'jobTemplateId' => 'job_template_id',
        'recuriterOfficeId'=>'recruiter_office_id',
        'jobType' => 'job_type',
        'noOfJobs' => 'no_of_jobs',
        'isMonday' => 'is_monday',
        'isTuesday'=>'is_tuesday',
        'isWednesday' => 'is_wednesday',
        'isThursday' => 'is_thursday',
        'isFriday' => 'is_friday',
        'isSaturday'=>'is_saturday',
        'isSunday' => 'is_sunday',
        'isPublished' => 'is_published',
        ];
    protected $hidden       = ['created_at','updated_at','deleted_at'];
    protected $fillable     = ['jobTemplateId','recuriterOfficeId','jobType','noOfJobs','isMonday',
        'isTuesday','isWednesday','isThursday','isFriday','isSaturday','isSunday','isPublished'];
    //protected $appends      = ['userId','templateName','templateDesc'];

    protected $dates = ['deleted_at'];
    
    public function tempJobDates(){
        return $this->hasMany(TempJobDates::class,'recruiter_job_id');
    }
    
}
    