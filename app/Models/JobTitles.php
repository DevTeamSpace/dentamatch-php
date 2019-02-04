<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\JobTitles
 *
 * @property int $id
 * @property string $jobtitle_name
 * @property string|null $short_name
 * @property int|null $parent_id
 * @property string $mapped_skills_id
 * @property int|null $is_license_required
 * @property int $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereIsLicenseRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereJobtitleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereMappedSkillsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobTitles whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JobTitles extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    protected $table = 'job_titles';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'JobtitleName' => 'jobtitle_name',
        'isLicenseRequired' => 'is_license_required',
        ];
    protected $hidden       = ['created_at','updated_at'];
    
    public static function getAll($active='',$template=''){
        $obj = self::select('id','jobtitle_name','is_license_required');
        if($active!=''){
            $obj->where('is_active',$active)->orderby('id','asc');
        }
        if($template!=''){
            $obj->whereNull('parent_id')->orderby('id','asc');
        }
        return $obj->get()->toArray();
       
    }

    public static function getTitle($titleId){
        $obj = self::select('jobtitle_name','is_license_required');
        $obj->where('id',$titleId);
        $obj->where('is_active',JobTitles::ACTIVE);
        return $obj->first()->toArray();
    }
}