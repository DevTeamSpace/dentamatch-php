<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Models\TemplateSkills
 *
 * @property int $id
 * @property int $job_template_id
 * @property int $skill_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string|null $mapping_for
 * @property-read JobTemplates $template
 * @property-read Skills $skill
 *
 * @method static Builder|TemplateSkills newModelQuery()
 * @method static Builder|TemplateSkills newQuery()
 * @method static Builder|TemplateSkills query()
 * @method static Builder|TemplateSkills whereCreatedAt($value)
 * @method static Builder|TemplateSkills whereId($value)
 * @method static Builder|TemplateSkills whereJobTemplateId($value)
 * @method static Builder|TemplateSkills whereSkillId($value)
 * @method static Builder|TemplateSkills whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TemplateSkills extends Model
{
    use Eloquence, Mappable;

    protected $table = 'template_skills';

    protected $guarded = ['id'];
    protected $maps = [
        'jobTemplateId' => 'job_template_id',
        'skillId'       => 'skill_id',
    ];
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['jobTemplateId', 'skillId'];
    protected $appends = ['jobTemplateId', 'skillId'];

    public function template()
    {
        return $this->belongsTo(JobTemplates::class, 'job_template_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skills::class);
    }

    public static function getTemplateSkills($templateId)
    {
        return TemplateSkills::where('job_template_id', $templateId)
            ->join('skills', 'template_skills.skill_id', '=', 'skills.id')
            ->join('skills as sk', 'sk.id', '=', 'skills.parent_id')
            ->where('skills.is_active', Skills::ACTIVE)->where('skills.parent_id', '<>', null)
            ->select('sk.skill_name as parent_skill_name', DB::raw('group_concat(skills.skill_name SEPARATOR ", ") as skill_name'))
            ->orderBy('skills.skill_name', 'desc')
            ->orderBy('skills.parent_id', 'asc')
            ->groupBy('skills.parent_id')
            ->get()->toArray();

    }
}
