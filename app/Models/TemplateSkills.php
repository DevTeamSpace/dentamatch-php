<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use DB;

class TemplateSkills extends Model
{
    use Eloquence, Mappable;
    
    protected $table = 'template_skills';
    protected $primaryKey = 'id';
    
    protected $guarded = ['id'];
    protected $maps          = [
        'jobTemplateId' => 'job_template_id',
        'skillId' => 'skill_id',
        ];
    protected $hidden       = ['created_at','updated_at'];
    protected $fillable     = ['jobTemplateId','skillId'];
    protected $appends      = ['jobTemplateId','skillId'];
    
    public static function getTemplateSkills($templateId){
        return TemplateSkills::where('job_template_id',$templateId)
                ->join('skills','template_skills.skill_id','=','skills.id')
                ->join('skills as sk','sk.id','=','skills.parent_id')
                ->where('skills.is_active',  Skills::ACTIVE)->where('skills.parent_id','<>',null)
                ->select('sk.skill_name as parent_skill_name',DB::raw('group_concat(skills.skill_name) as skill_name'))
                ->orderBy('skills.parent_id','asc')
                ->groupBy('skills.parent_id')
                ->get()->toArray();
        
    }
}
