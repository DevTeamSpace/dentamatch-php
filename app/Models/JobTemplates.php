<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTemplates extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;
    
    protected $table = 'job_templates';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'userId' => 'user_id',
        'jobTitleId'=>'job_title_id',
        'templateName' => 'template_name',
        'templateDesc' => 'template_desc',
        ];
    protected $hidden       = ['created_at','updated_at','deleted_at'];
    protected $fillable     = ['userId','templateName','templateDesc'];
    //protected $appends      = ['userId','templateName','templateDesc'];

    protected $dates = ['deleted_at'];
    
     /**
     * Get the skiild for the blog post.
     */
    public function templateSkills(){
        return $this->hasMany(TemplateSkills::class,'job_template_id');
    }
    
    /**
     * Encrypt Template id.
     *
     * @param  string  $value
     * @return string
     */
    public function getIdAttribute($value){
        return ($value);//encrypt
    }
    
    public static function getIdDecrypt($value){
        return ($value);//decrypt
    }
    
    public static function findById($id,$userId=''){
        $templateId = self::getIdDecrypt($id);
        $tempObj = JobTemplates::where('job_templates.id',$templateId);
        if($userId!=''){
            $tempObj->where('user_id',$userId)
                ->join('job_titles','job_templates.job_title_id','=','job_titles.id')
                ->select('job_titles.jobtitle_name','job_templates.*');
        }
        return $tempObj->first();
    }
    
    public static function getAllUserTemplates($userId){
        return JobTemplates::join('job_titles','job_templates.job_title_id','=','job_titles.id')
                ->where('job_templates.user_id',$userId)
                ->select('job_titles.jobtitle_name','job_templates.template_name','job_templates.id')->get()->toArray();
    }
}
    