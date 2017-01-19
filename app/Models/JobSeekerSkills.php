<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSeekerSkills extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    
    protected $table = 'jobseeker_skills';
    protected $primaryKey = 'id';
    
    
    protected $hidden       = ['deleted_at','created_at','updated_at'];
    
    public static function getJobSeekerSkills($userId)
    {
        $query = static::select('skills.id as parentId', 'skillsChild.id as childId', 
                    'skills.skill_name as skillsName', 'skillsChild.skill_name as skillsChildName',
                    'jobseeker_skills.other_skill as otherSkills')
                ->join('skills AS skillsChild','jobseeker_skills.skill_id', '=', 'skillsChild.id')
                ->join('skills', 'skills.id','=', 'skillsChild.parent_id')
                ->where('skills.is_active', static::ACTIVE)
                ->where('jobseeker_skills.user_id', $userId)
                ->where('skills.parent_id',0)
                ->orderBy('skills.id')
                ->orderBy('skillsChild.id');
        
        $list = $query->get()->toArray();

        return $list;
    }
    
}
