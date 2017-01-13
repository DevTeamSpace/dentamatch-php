<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model {

    protected $table = 'jobseeker_work_experiences';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'job_title_id', 'months_of_expereince', 'office_name', 'office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email'];
    
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at'
    ];
    
    /**
     * Method to return the list of experiences with pagination
     * @param type $userId
     * @param type $start
     * @param type $limit
     * @return type
     */
    public static function getWorkExperienceList($userId, $start = 0, $limit = "")
    {
        $data = array("list"=>[], "total"=>0);
        
        $query = WorkExperience::select('jobseeker_work_experiences.id','jobseeker_work_experiences.user_id', 'jobseeker_work_experiences.job_title_id', 'jobseeker_work_experiences.months_of_expereince',
                'job_titles.jobtitle_name', 'jobseeker_work_experiences.office_name', 'jobseeker_work_experiences.office_address', 'jobseeker_work_experiences.city', 'jobseeker_work_experiences.user_id',
                'jobseeker_work_experiences.reference1_name', 'jobseeker_work_experiences.reference1_mobile', 'jobseeker_work_experiences.reference1_email',
                'jobseeker_work_experiences.reference2_name', 'jobseeker_work_experiences.reference2_mobile', 'jobseeker_work_experiences.reference2_email',
                'jobseeker_work_experiences.created_at')
                ->join('job_titles','job_titles.id','=','jobseeker_work_experiences.job_title_id')
                ->where('user_id', $userId)
                ->orderBy('jobseeker_work_experiences.created_at', 'asc');
        
        $total = count($query->get());
        
        if($limit) {
            $query->skip($start)->take($limit);
        }

        $list = $query->get();

        $data['list']  = $list;
        $data['total'] = $total;
        
        return $data;
    }

}
