<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

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
}
