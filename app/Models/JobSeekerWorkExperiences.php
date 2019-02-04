<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\JobSeekerWorkExperiences
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
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerWorkExperiences onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereJobTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereMonthsOfExpereince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereOfficeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereOfficeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereReference1Email($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereReference1Mobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereReference1Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereReference2Email($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereReference2Mobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereReference2Name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerWorkExperiences whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerWorkExperiences withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerWorkExperiences withoutTrashed()
 * @mixin \Eloquent
 */
class JobSeekerWorkExperiences extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_work_experiences';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
       'updated_at', 'deleted_at','created_at'
    ];
    
    public static function getParentWorkExperiences($userId){
        $work = [];
        if($userId){
            $work = JobSeekerWorkExperiences::where('jobseeker_work_experiences.user_id',$userId)
                            ->leftJoin('job_titles','jobseeker_work_experiences.job_title_id','=','job_titles.id')
                            ->select('months_of_expereince','office_name','office_address', 'city', 'reference1_name', 'reference1_mobile', 'reference1_email', 'reference2_name', 'reference2_mobile', 'reference2_email', 'job_titles.jobtitle_name')
                            ->get()
                            ->toArray();
        }
        return $work;
    }
    
}
