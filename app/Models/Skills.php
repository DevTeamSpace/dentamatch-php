<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    
    protected $table = 'skills';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'skillName' => 'skill_name',
        ];
    protected $hidden       = ['is_active','created_at','updated_at'];
    
   
    public function parent()
    {
        return $this->belongsTo(Skills::class,'parent_id')->where('parent_id',null)->where('is_active',1);
    }

    public function children()
    {
        return $this->hasMany(Skills::class,'parent_id')->where('is_active',1)->where('parent_id','<>',null);
    }
    
    public static function getAllParentChildSkillList(){
        return Skills::join('skills as sk','sk.id','=','skills.parent_id')
                ->where('skills.is_active',  Skills::ACTIVE)->where('skills.parent_id','<>',null)
                ->select('skills.id','skills.skill_name','skills.parent_id','sk.skill_name as parent_skill_name')
                ->get()->groupBy('parent_skill_name')->toArray();
    }
}