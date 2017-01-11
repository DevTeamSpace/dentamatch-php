<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model {

    protected $table = 'jobseeker_work_experiences';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'job_title_id', 'months_of_expereince', 'office_name', 'office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email'];
    
    /**
     * Method to return the list of experiences with pagination
     * @param type $userId
     * @param type $start
     * @param type $limit
     * @return type
     */
    public static function getWorkExperienceList($userId, $start = 0, $limit = "")
    {
        $workExperience = array("list"=>[], "total"=>0);
        
        $query = WorkExperience::where('user_id', $userId);
        
        $total = count($query->get());
        
        if($limit) {
            $query->skip($start)->take($limit);
        }

        $list = $query->get();

        $workExperience['list']  = $list;
        $workExperience['total'] = $total;
        
        return $workExperience;
    }

}
