<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class JobSeekerWorkExperiences extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_work_experiences';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at','created_at'
    ];
    
    public static function getParentWorkExperiences($userId){
        $work = [];
        if($userId){
            $work = JobSeekerWorkExperiences::where('jobseeker_work_experiences.user_id',$userId)
                            ->leftJoin('job_titles','jobseeker_work_experiences.job_title_id','=','job_titles.id')
                            ->select('months_of_expereince','office_name','office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email', 'job_titles.jobtitle_name')
                            ->get()
                            ->toArray();
        }
        return $work;
    }
    
}
