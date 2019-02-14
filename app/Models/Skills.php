<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\Skills
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $skill_name
 * @property int $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Skills[] $children
 * @property-read Skills|null $parent
 *
 * @method static Builder|Skills newModelQuery()
 * @method static Builder|Skills newQuery()
 * @method static Builder|Skills query()
 * @method static Builder|Skills whereCreatedAt($value)
 * @method static Builder|Skills whereId($value)
 * @method static Builder|Skills whereIsActive($value)
 * @method static Builder|Skills whereParentId($value)
 * @method static Builder|Skills whereSkillName($value)
 * @method static Builder|Skills whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Skills extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'skills';
    protected $primaryKey = 'id';

    protected $maps = [
        'skillName' => 'skill_name',
    ];
    protected $hidden = ['created_at', 'updated_at'];


    public function parent()
    {
        return $this->belongsTo(Skills::class, 'parent_id')->whereNull('parent_id')->where('is_active', 1);
    }

    public function children()
    {
        return $this->hasMany(Skills::class, 'parent_id')->where('is_active', 1)->whereNotNull('parent_id');
    }

    public static function getAllParentChildSkillList($templateId = '')
    {
        $skillObj = Skills::join('skills as sk', 'sk.id', '=', 'skills.parent_id')
            ->where('skills.is_active', Skills::ACTIVE)->where('skills.parent_id', '<>', null);

        if ($templateId != '') {
            $skillObj->leftJoin('template_skills as tsk', function ($query) use ($templateId) {
                $query->on('tsk.skill_id', '=', 'skills.id')
                    ->where('tsk.job_template_id', $templateId);
            })
                ->select('skills.id', 'skills.skill_name', 'skills.parent_id', 'sk.skill_name as parent_skill_name', 'tsk.skill_id as sel_skill_id');
        } else {
            $skillObj->select('skills.id', 'skills.skill_name', 'skills.parent_id', 'sk.skill_name as parent_skill_name');
        }
        return $skillObj->orderBy('sk.skill_name', 'asc')->orderBy('skills.id', 'asc')->get()->groupBy('parent_skill_name')->toArray();
    }
}