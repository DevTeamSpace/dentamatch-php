<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\ApiResponse;
use Illuminate\Support\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string|null $password
 * @property int $is_active
 * @property string $verification_code
 * @property int $is_verified
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read UserGroup $userGroup
 * @property-read JobSeekerProfiles $seekerProfile
 * @property-read RecruiterProfile $recruiterProfile
 * @property-read Device[]|Collection $devices
 * @property-read JobLists[]|Collection $appliedJobs
 * @property-read User[]|Collection $favouriteSeekers
 * @property-read Affiliation[]|Collection $affiliations
 * @property-read Certifications[]|Collection $certificates
 * @property-read Schooling[]|Collection $schooling
 * @property-read Skills[]|Collection $skills
 * @property-read JobSeekerTempAvailability[]|Collection $tempDates
 * @property-read JobSeekerWorkExperiences[]|Collection $workExperience
 * @property-read JobTemplates[]|Collection $jobTemplates
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereCreatedBy($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereIsActive($value)
 * @method static Builder|User whereIsVerified($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUpdatedBy($value)
 * @method static Builder|User whereVerificationCode($value)
 *
 * @method static Builder|User active()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verification_code', 'is_verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userGroup()
    {
        return $this->hasOne(UserGroup::class);
    }

    public function seekerProfile()
    {
        return $this->hasOne(JobSeekerProfiles::class, 'user_id');
    }

    public function recruiterProfile()
    {
        return $this->hasOne(RecruiterProfile::class, 'user_id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function appliedJobs()
    {
        return $this->hasMany(JobLists::class, 'seeker_id');
    }

    public function tempDates()
    {
        return $this->hasMany(JobSeekerTempAvailability::class, 'user_id');
    }

    public function workExperience()
    {
        return $this->hasMany(JobSeekerWorkExperiences::class, 'user_id');
    }

    public function jobTemplates()
    {
        return $this->hasMany(JobTemplates::class, 'user_id');
    }

    public function favouriteSeekers()
    {
        return $this->belongsToMany(User::class, 'favourites', 'recruiter_id', 'seeker_id');
    }

    public function affiliations()
    {
        return $this->belongsToMany(Affiliation::class, 'jobseeker_affiliations', 'user_id', 'affiliation_id')
            ->withTimestamps()
            ->whereNull('jobseeker_affiliations.deleted_at')
            ->withPivot('other_affiliation');
    }

    public function certificates()
    {
        return $this->belongsToMany(Certifications::class, 'jobseeker_certificates', 'user_id', 'certificate_id')
            ->withTimestamps()
            ->whereNull('jobseeker_certificates.deleted_at')
            ->withPivot(['image_path', 'validity_date']);
    }

    public function schooling()
    {
        return $this->belongsToMany(Schooling::class, 'jobseeker_schoolings', 'user_id', 'schooling_id')
            ->withTimestamps()
            ->whereNull('jobseeker_schoolings.deleted_at') // todo schooling active?
            ->withPivot(['other_schooling', 'year_of_graduation']);
    }

    public function skills()
    {
        return $this->belongsToMany(Skills::class, 'jobseeker_skills', 'user_id', 'skill_id')
            ->withTimestamps()
            ->whereNull('jobseeker_skills.deleted_at') // todo skill active?
            ->withPivot(['other_skill']);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public static function validateRecruiterEmail($email)
    {
        return static::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->where('user_groups.group_id', UserGroup::RECRUITER)
            ->where('users.email', $email)
            ->count();
    }

    public static function getAdminUserDetailsForNotification()
    {
        return static::active()->whereHas('userGroup', function ($query) {
            $query->where('group_id', UserGroup::ADMIN);
        })->first();
    }

    public static function getAllUserByRole($groupId)
    {
        return static::where('is_active', 1)->where('group_id', $groupId)
            ->join('user_groups', 'users.id', '=', 'user_groups.user_id')->get();
    }

    public static function getUser($userId)
    {
        $return = [];
        $userModel = static::select(['users.id', 'users.email', 'users.is_verified',
                                     'jobseeker_profiles.first_name',
                                     'jobseeker_profiles.last_name',
                                     'jobseeker_profiles.zipcode as zipCode',
                                     'jobseeker_profiles.latitude',
                                     'jobseeker_profiles.longitude',
                                     'jobseeker_profiles.preferred_job_location',
                                     'jobseeker_profiles.preferred_job_location_id',
                                     'preferred_job_locations.preferred_location_name',
                                     'jobseeker_profiles.preferred_city',
                                     'jobseeker_profiles.preferred_state',
                                     'jobseeker_profiles.preferred_country',
                                     'jobseeker_profiles.job_titile_id',
                                     'job_titles.jobtitle_name',
                                     'jobseeker_profiles.profile_pic',
                                     'jobseeker_profiles.dental_state_board',
                                     'jobseeker_profiles.license_number',
                                     'jobseeker_profiles.state',
                                     'jobseeker_profiles.signup_source',
                                     'jobseeker_profiles.preferred_job_location_id',
                                     'jobseeker_profiles.is_completed as profile_completed',
                                     'jobseeker_profiles.is_job_seeker_verified',
                                     'jobseeker_profiles.about_me',
                                     'devices.user_token as access_token'])
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', 'users.id')
            ->join('devices', 'devices.user_id', 'users.id')
            ->leftjoin('job_titles', 'job_titles.id', 'jobseeker_profiles.job_titile_id')
            ->leftjoin('preferred_job_locations', 'preferred_job_locations.id', 'jobseeker_profiles.preferred_job_location_id')
            ->where('users.id', $userId)
            ->first();

        if ($userModel) {
            $profilePic = "";
            $dentalStateBoard = "";
            $return = $userModel->toArray();

            if ($return['profile_pic'] && $return['profile_pic'] != "") {
                $profilePic = config('services.aws.url') . DIRECTORY_SEPARATOR . config('services.aws.bucket') . DIRECTORY_SEPARATOR . $return['profile_pic'];
            }

            if (($return['dental_state_board']) && $return['dental_state_board'] != "") {
                $dentalStateBoard = ApiResponse::getThumbImage($return['dental_state_board']);
            }
            $return['image_url'] = $profilePic;
            $return['dental_state_board'] = $dentalStateBoard;
        }
        return $return;
    }

    public static function isUserEmailVerified($userId)
    {
        return static::select(['is_verified', 'first_name', 'email', 'verification_code'])
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', 'users.id')
            ->where('users.id', $userId)->first();
    }
}
