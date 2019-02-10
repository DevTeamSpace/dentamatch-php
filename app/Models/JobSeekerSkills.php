<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\JobSeekerSkills
 *
 * @property int $id
 * @property int $user_id
 * @property int $skill_id
 * @property string $other_skill
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @method static Builder|JobSeekerSkills newModelQuery()
 * @method static Builder|JobSeekerSkills newQuery()
 * @method static Builder|JobSeekerSkills query()
 * @method static Builder|JobSeekerSkills whereCreatedAt($value)
 * @method static Builder|JobSeekerSkills whereDeletedAt($value)
 * @method static Builder|JobSeekerSkills whereId($value)
 * @method static Builder|JobSeekerSkills whereOtherSkill($value)
 * @method static Builder|JobSeekerSkills whereSkillId($value)
 * @method static Builder|JobSeekerSkills whereUpdatedAt($value)
 * @method static Builder|JobSeekerSkills whereUserId($value)
 * @mixin \Eloquent
 */
class JobSeekerSkills extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 0;

    protected $table = 'jobseeker_skills';
    protected $primaryKey = 'id';

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public static function getJobSeekerSkills($userId)
    {
        $query = static::select('skills.id as parentId', 'skillsChild.id as childId',
            'skills.skill_name as skillsName', 'skillsChild.skill_name as skillsChildName',
            'jobseeker_skills.other_skill as otherSkills')
            ->join('skills AS skillsChild', 'jobseeker_skills.skill_id', '=', 'skillsChild.id')
            ->join('skills', 'skills.id', '=', 'skillsChild.parent_id')
            ->where('skills.is_active', static::ACTIVE)
            ->where('jobseeker_skills.user_id', $userId)
            ->where('skills.parent_id', 0)
            ->groupBy('parentId')
            ->groupBy('childId')
            ->orderBy('skills.id')
            ->orderBy('skillsChild.id');

        $list = $query->get()->toArray();

        return $list;
    }

    public static function getAllJobSeekerSkills($userIds)
    {
        $result = [];

        if ($userIds) {
            $allSkills = JobSeekerSkills::whereIn('jobseeker_skills.user_id', $userIds)
                ->where('skills.is_active', JobSeekerSkills::ACTIVE)
                ->where('skills.parent_id', '!=', 0)
                ->leftJoin('skills', 'jobseeker_skills.skill_id', '=', 'skills.id')
                ->leftJoin('skills as skill_title', 'skills.parent_id', '=', 'skill_title.id')
                ->select('jobseeker_skills.other_skill', 'skills.skill_name', 'skill_title.skill_name as skill_title', 'jobseeker_skills.user_id', 'skills.parent_id')
                ->orderBy('skills.parent_id', 'desc')
                ->orderBy('skills.skill_name', 'ASC')
                ->get();
            foreach ($allSkills as $value) {
                $result[$value->user_id][$value->parent_id]['title'] = $value->skill_title;
                $result[$value->user_id][$value->parent_id]['skills'][] = $value->skill_name;
            }
        }
        return $result;

    }

    public static function getParentJobSeekerSkills($userId)
    {
        $skills = [];
        if ($userId) {
            $skills = static::where('jobseeker_skills.user_id', $userId)
                ->where('skills.is_active', static::ACTIVE)
                ->whereNotNull('skills.parent_id')
                ->leftJoin('skills', 'jobseeker_skills.skill_id', '=', 'skills.id')
                ->leftJoin('skills as skill_title', 'skills.parent_id', '=', 'skill_title.id')
                ->select('jobseeker_skills.other_skill', 'skills.skill_name', 'skill_title.skill_name as skill_title')
                ->groupby('skills.parent_id')
                ->orderBy('skills.parent_id', 'desc')
                ->addSelect(DB::raw("group_concat(skills.skill_name SEPARATOR ', ') AS skill_name"))
                ->get()
                ->toArray();
        }

        return $skills;
    }

    public static function getJobseekerOtherSkills($userId)
    {
        $killsModel = Skills::select('id')->where('parent_id', 0)->get()->toArray();
        foreach ($killsModel as $value) {
            $skillsId[] = $value['id'];
        }

        $query = static::select('skills.id as parentId', 'skills.id as childId',
            'skills.skill_name as skillsName', 'jobseeker_skills.other_skill as skillsChildName',
            'jobseeker_skills.other_skill as otherSkills')
            ->join('skills', 'skills.id', '=', 'jobseeker_skills.skill_id')
            ->where('skills.is_active', static::ACTIVE)
            ->where('jobseeker_skills.user_id', $userId)
            ->whereIn('skill_id', $skillsId);

        $list = $query->get()->toArray();

        return $list;
    }

    public static function addJobSeekerSkills($userId, $skillsArray)
    {
        if (!empty($skillsArray)) {
            foreach ($skillsArray as $skills) {
                $skillInsertArray[] = ['user_id' => $userId, 'skill_id' => $skills, 'other_skill' => ""];
            }
            self::insert($skillInsertArray);
        }
    }

    public static function fetchJobseekerSkills($userId)
    {
        $jobseekerSkills = static::select('jobseeker_skills.skill_id')
            ->where("user_id", $userId)
            ->orderBy('jobseeker_skills.skill_id')
            ->get()
            ->map(function ($jobseekerSkills) {
                return $jobseekerSkills['skill_id'];
            })->toArray();

        return $jobseekerSkills;
    }
}
