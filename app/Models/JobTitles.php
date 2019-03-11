<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Builder|JobTitles newModelQuery()
 * @method static Builder|JobTitles newQuery()
 * @method static Builder|JobTitles query()
 * @method static Builder|JobTitles whereCreatedAt($value)
 * @method static Builder|JobTitles whereId($value)
 * @method static Builder|JobTitles whereIsActive($value)
 * @method static Builder|JobTitles whereIsLicenseRequired($value)
 * @method static Builder|JobTitles whereJobtitleName($value)
 * @method static Builder|JobTitles whereMappedSkillsId($value)
 * @method static Builder|JobTitles whereParentId($value)
 * @method static Builder|JobTitles whereShortName($value)
 * @method static Builder|JobTitles whereUpdatedAt($value)
 * @method static Builder|JobTitles active()
 * @mixin \Eloquent
 */
class JobTitles extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    protected $table = 'job_titles';

    protected $hidden = ['created_at', 'updated_at'];

    public function scopeActive(Builder $query) {
        return $query->where('is_active', self::ACTIVE);
    }

    public static function getAll()
    {
        return self::select(['id', 'jobtitle_name', 'is_license_required'])
            ->active()->orderby('id')
            ->get()->toArray();
    }

    public static function getTitle($titleId)
    {
        $obj = self::select(['jobtitle_name', 'is_license_required']);
        $obj->where('id', $titleId)->active();
        return $obj->first()->toArray();
    }
}