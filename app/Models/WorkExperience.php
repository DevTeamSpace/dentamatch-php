<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\WorkExperience
 *
 * @property int $id
 * @property int $user_id
 * @property int $job_title_id
 * @property int $months_of_expereince
 * @property string $office_name
 * @property string $office_address
 * @property string $city
 * @property string|null $state
 * @property string|null $reference1_name
 * @property string|null $reference1_mobile
 * @property string|null $reference1_email
 * @property string|null $reference2_name
 * @property string|null $reference2_mobile
 * @property string|null $reference2_email
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience newQuery()
 * @method static \Illuminate\Database\Query\Builder|WorkExperience onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereJobTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereMonthsOfExpereince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereOfficeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereOfficeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereReference1Email($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereReference1Mobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereReference1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereReference2Email($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereReference2Mobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereReference2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkExperience whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|WorkExperience withTrashed()
 * @method static \Illuminate\Database\Query\Builder|WorkExperience withoutTrashed()
 * @mixin \Eloquent
 *  todo this and JobSeekerWorkExperience??
 */
class WorkExperience extends Model
{
    protected $table = 'jobseeker_work_experiences';

    protected $fillable = ['user_id', 'job_title_id', 'months_of_expereince', 'office_name', 'office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    /**
     * Method to return the list of experiences with pagination
     * @param integer $userId
     * @param integer $start
     * @param integer $limit
     * @return array
     */
    public static function getWorkExperienceList($userId, $start = 0, $limit = 0)
    {
        $data = ["list" => [], "total" => 0];

        $query = WorkExperience::select('jobseeker_work_experiences.id', 'jobseeker_work_experiences.user_id', 'jobseeker_work_experiences.job_title_id', 'jobseeker_work_experiences.months_of_expereince',
            'job_titles.jobtitle_name', 'jobseeker_work_experiences.office_name', 'jobseeker_work_experiences.office_address', 'jobseeker_work_experiences.city', 'jobseeker_work_experiences.user_id',
            'jobseeker_work_experiences.state', 'jobseeker_work_experiences.reference1_name', 'jobseeker_work_experiences.reference1_mobile', 'jobseeker_work_experiences.reference1_email',
            'jobseeker_work_experiences.reference2_name', 'jobseeker_work_experiences.reference2_mobile', 'jobseeker_work_experiences.reference2_email',
            'jobseeker_work_experiences.created_at')
            ->join('job_titles', 'job_titles.id', '=', 'jobseeker_work_experiences.job_title_id')
            ->where('user_id', $userId)
            ->orderBy('jobseeker_work_experiences.created_at', 'asc');

        $total = count($query->get());

        if ($limit) {
            $query->skip($start)->take($limit);
        }

        $list = $query->get();

        $data['list'] = $list;
        $data['total'] = $total;

        return $data;
    }

}
