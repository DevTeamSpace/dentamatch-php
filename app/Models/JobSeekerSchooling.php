<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * App\Models\JobSeekerSchooling
 *
 * @property int $id
 * @property int $user_id
 * @property int $schooling_id
 * @property string $other_schooling
 * @property string $year_of_graduation
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User $seeker
 * @property-read Schooling $schooling
 *
 * @method static bool|null forceDelete()
 * @method static Builder|JobSeekerSchooling newModelQuery()
 * @method static Builder|JobSeekerSchooling newQuery()
 * @method static QueryBuilder|JobSeekerSchooling onlyTrashed()
 * @method static Builder|JobSeekerSchooling query()
 * @method static bool|null restore()
 * @method static Builder|JobSeekerSchooling whereCreatedAt($value)
 * @method static Builder|JobSeekerSchooling whereDeletedAt($value)
 * @method static Builder|JobSeekerSchooling whereId($value)
 * @method static Builder|JobSeekerSchooling whereOtherSchooling($value)
 * @method static Builder|JobSeekerSchooling whereSchoolingId($value)
 * @method static Builder|JobSeekerSchooling whereUpdatedAt($value)
 * @method static Builder|JobSeekerSchooling whereUserId($value)
 * @method static Builder|JobSeekerSchooling whereYearOfGraduation($value)
 * @method static QueryBuilder|JobSeekerSchooling withTrashed()
 * @method static QueryBuilder|JobSeekerSchooling withoutTrashed()
 * @mixin \Eloquent
 */
class JobSeekerSchooling extends Model
{
    use SoftDeletes;

    protected $table = 'jobseeker_schoolings';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    public function seeker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function schooling()
    {
        return $this->belongsTo(Schooling::class);
    }

    public static function getUserSchoolingList($userId)
    {
        $query = static::where('user_id', $userId)->orderBy('id');
        $list = $query->get()->toArray();

        return $list;
    }

    public static function getJobSeekerSchooling($userId)
    {
        $query = static::select('schoolings.id as parentId', 'schoolingsChild.id as childId',
            'schoolings.school_name', 'schoolingsChild.school_name as school_title',
            'jobseeker_schoolings.year_of_graduation', 'jobseeker_schoolings.other_schooling as otherSchooling')
            ->join('schoolings AS schoolingsChild', 'jobseeker_schoolings.schooling_id', '=', 'schoolingsChild.id')
            ->join('schoolings', 'schoolings.id', '=', 'schoolingsChild.parent_id')
            ->where('schoolings.is_active', 1)
            ->where('jobseeker_schoolings.user_id', $userId)
            ->whereNull('schoolings.parent_id')
            ->orderBy('schoolings.id')
            ->orderBy('schoolingsChild.id');

        $list = $query->get()->toArray();

        return $list;
    }

    public static function getJobseekerOtherSchooling($userId)
    {
        $schoolId = [];
        $schoolingModel = Schooling::select('id')->whereNull('parent_id')->get()->toArray();
        foreach ($schoolingModel as $value) {
            $schoolId[] = $value['id'];
        }

        $query = static::select('schoolings.id',
            'schoolings.school_name',
            'jobseeker_schoolings.year_of_graduation', 'jobseeker_schoolings.other_schooling as school_title')
            ->join('schoolings', 'schoolings.id', '=', 'jobseeker_schoolings.schooling_id')
            ->whereIn('schooling_id', $schoolId)
            ->where('user_id', $userId)
            ->orderBy('schoolings.id');
        $list = $query->get()->toArray();

        return $list;
    }

    public static function getParentJobSeekerSchooling($userId)
    {
        $schoolings = [];
        if ($userId) {
            $queryParent = static::select('schoolings.id as parentId', 'schoolingsChild.id as childId',
                'schoolings.school_name as school_name', 'schoolingsChild.school_name as school_title',
                'jobseeker_schoolings.year_of_graduation as year_of_graduation', 'jobseeker_schoolings.other_schooling as otherSchooling')
                ->join('schoolings AS schoolingsChild', 'jobseeker_schoolings.schooling_id', '=', 'schoolingsChild.id')
                ->join('schoolings', 'schoolings.id', '=', 'schoolingsChild.parent_id')
                ->where('schoolings.is_active', 1)
                ->where('jobseeker_schoolings.user_id', $userId)
                ->whereNull('schoolings.parent_id')
                ->orderBy('schoolings.id')
                ->orderBy('schoolingsChild.id');

            $listParent = $queryParent->get()->toArray();

            $schoolId = [];
            $schoolingModel = Schooling::select('id')->whereNull('parent_id')->get()->toArray();
            foreach ($schoolingModel as $value) {
                $schoolId[] = $value['id'];
            }

            $queryOther = static::select('schoolings.id as parentId',
                'schoolings.school_name as school_name',
                'jobseeker_schoolings.year_of_graduation as year_of_graduation', 'jobseeker_schoolings.other_schooling as school_title')
                ->join('schoolings', 'schoolings.id', '=', 'jobseeker_schoolings.schooling_id')
                ->whereIn('schooling_id', $schoolId)
                ->where('user_id', $userId);
            $listOther = $queryOther->get()->toArray();

            $schoolings = array_merge($listParent, $listOther);

        }

        return $schoolings;
    }

}
