<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\JobTemplates
 *
 * @property string $id
 * @property int $user_id
 * @property int $job_title_id
 * @property string $template_name
 * @property string $template_desc
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read string|null $mapping_for
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TemplateSkills[] $templateSkills
 * @property JobTitles $jobTitle
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates newQuery()
 * @method static \Illuminate\Database\Query\Builder|JobTemplates onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereJobTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereTemplateDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JobTemplates whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|JobTemplates withTrashed()
 * @method static \Illuminate\Database\Query\Builder|JobTemplates withoutTrashed()
 * @mixin \Eloquent
 */
class JobTemplates extends Model
{
    use Eloquence, Mappable;
    use SoftDeletes;

    protected $table = 'job_templates';
    protected $primaryKey = 'id';

    protected $maps = [
        'userId'       => 'user_id',
        'jobTitleId'   => 'job_title_id',
        'templateName' => 'template_name',
        'templateDesc' => 'template_desc',
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['userId', 'templateName', 'templateDesc'];

    protected $dates = ['deleted_at'];

    /**
     * Get the skills for the template
     */
    public function templateSkills()
    {
        return $this->hasMany(TemplateSkills::class, 'job_template_id');
    }

    public function jobTitle()
    {
        return $this->belongsTo(JobTitles::class);
    }

    /**
     * Encrypt Template id.
     *
     * @param  string $value
     * @return string
     */
    public function getIdAttribute($value)
    {
        return ($value);//encrypt
    }

    public static function getIdDecrypt($value)
    {
        return ($value);//decrypt
    }

    public static function findById($id, $userId = '')
    {
        $templateId = static::getIdDecrypt($id);
        $tempObj = JobTemplates::where('job_templates.id', $templateId);
        if ($userId != '') {
            $tempObj->where('user_id', $userId)
                ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
                ->select('job_titles.jobtitle_name', 'job_templates.*');
        }
        return $tempObj->first();
    }

    public static function getAllUserTemplates($userId)
    {
        return JobTemplates::join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
            ->where('job_templates.user_id', $userId)
            ->select('job_titles.jobtitle_name', 'job_templates.template_name', 'job_templates.id')->get()->toArray();
    }
}
    