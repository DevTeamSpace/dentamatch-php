<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Models\JobTemplates
 *
 * @property string $id
 * @property int $user_id
 * @property int $job_title_id
 * @property string $template_name
 * @property string $template_desc
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at               SOFT DELETE WAS REMOVED DUE TO LACK OF USING IT IN JOINs
 * @property-read string|null $mapping_for
 * @property-read Collection|TemplateSkills[] $templateSkills
 * @property JobTitles $jobTitle
 * @property-read User $recruiter
 *
 * @method static Builder|JobTemplates newModelQuery()
 * @method static Builder|JobTemplates newQuery()
 * @method static Builder|JobTemplates query()
 * @method static Builder|JobTemplates whereCreatedAt($value)
 * @method static Builder|JobTemplates whereDeletedAt($value)
 * @method static Builder|JobTemplates whereId($value)
 * @method static Builder|JobTemplates whereJobTitleId($value)
 * @method static Builder|JobTemplates whereTemplateDesc($value)
 * @method static Builder|JobTemplates whereTemplateName($value)
 * @method static Builder|JobTemplates whereUpdatedAt($value)
 * @method static Builder|JobTemplates whereUserId($value)
 * @mixin \Eloquent
 */
class JobTemplates extends Model
{
    use Eloquence, Mappable;

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

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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
    