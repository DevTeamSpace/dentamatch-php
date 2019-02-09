<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\ApiResponse;

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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $deleted_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Models\UserGroup $userGroup
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereVerificationCode($value)
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User active()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

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

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public static function validateRecuriterEmail($email)
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
        $userModel = static::select('users.id', 'users.email', 'users.is_verified',
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
            'devices.user_token as access_token')
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
                $profilePic = env('AWS_URL') . DIRECTORY_SEPARATOR . env('AWS_BUCKET') . DIRECTORY_SEPARATOR . $return['profile_pic'];
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
        return static::select('is_verified', 'first_name', 'email', 'verification_code')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', 'users.id')
            ->where('users.id', $userId)->first();
    }
}
