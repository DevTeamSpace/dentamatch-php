<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ApiResponse;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $profile_pic
 * @property string|null $zipcode
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $preferred_job_location_id
 * @property string|null $preferred_job_location
 * @property int|null $job_titile_id
 * @property string|null $dental_state_board
 * @property string|null $license_number
 * @property string|null $state
 * @property string|null $about_me
 * @property int $is_completed
 * @property int $is_job_seeker_verified 0=Not Verified,1=>Approved,2=>Reject
 * @property int $is_fulltime
 * @property int $is_parttime_monday
 * @property int $is_parttime_tuesday
 * @property int $is_parttime_wednesday
 * @property int $is_parttime_thursday
 * @property int $is_parttime_friday
 * @property int $is_parttime_saturday
 * @property int $is_parttime_sunday
 * @property int $signup_source 1=>App, 2=>Web
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $preferred_city
 * @property string|null $preferred_state
 * @property string|null $preferred_country
 * @property-read User $user
 * @method static Builder|UserProfile newModelQuery()
 * @method static Builder|UserProfile newQuery()
 * @method static Builder|UserProfile query()
 * @method static Builder|UserProfile whereAboutMe($value)
 * @method static Builder|UserProfile whereCreatedAt($value)
 * @method static Builder|UserProfile whereDentalStateBoard($value)
 * @method static Builder|UserProfile whereFirstName($value)
 * @method static Builder|UserProfile whereId($value)
 * @method static Builder|UserProfile whereIsCompleted($value)
 * @method static Builder|UserProfile whereIsFulltime($value)
 * @method static Builder|UserProfile whereIsJobSeekerVerified($value)
 * @method static Builder|UserProfile whereIsParttimeFriday($value)
 * @method static Builder|UserProfile whereIsParttimeMonday($value)
 * @method static Builder|UserProfile whereIsParttimeSaturday($value)
 * @method static Builder|UserProfile whereIsParttimeSunday($value)
 * @method static Builder|UserProfile whereIsParttimeThursday($value)
 * @method static Builder|UserProfile whereIsParttimeTuesday($value)
 * @method static Builder|UserProfile whereIsParttimeWednesday($value)
 * @method static Builder|UserProfile whereJobTitileId($value)
 * @method static Builder|UserProfile whereLastName($value)
 * @method static Builder|UserProfile whereLatitude($value)
 * @method static Builder|UserProfile whereLicenseNumber($value)
 * @method static Builder|UserProfile whereLongitude($value)
 * @method static Builder|UserProfile wherePreferredCity($value)
 * @method static Builder|UserProfile wherePreferredCountry($value)
 * @method static Builder|UserProfile wherePreferredJobLocation($value)
 * @method static Builder|UserProfile wherePreferredJobLocationId($value)
 * @method static Builder|UserProfile wherePreferredState($value)
 * @method static Builder|UserProfile whereProfilePic($value)
 * @method static Builder|UserProfile whereSignupSource($value)
 * @method static Builder|UserProfile whereState($value)
 * @method static Builder|UserProfile whereUpdatedAt($value)
 * @method static Builder|UserProfile whereUserId($value)
 * @method static Builder|UserProfile whereZipcode($value)
 * @mixin \Eloquent
 * todo why this and JobSeeker profile?
 */
class UserProfile extends Model
{
    protected $table = 'jobseeker_profiles';

    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'preferred_job_location_id', 'preferred_location_name',
        'job_titile_id', 'jobtitle_name', 'license_number', 'state', 'preferred_job_location_id',
        'is_job_seeker_verified', 'about_me', 'profile_pic', 'dental_state_board'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserProfile($userId)
    {
        $return = [];

        $userModel = static::select('jobseeker_profiles.id',
            'jobseeker_profiles.user_id',
            'jobseeker_profiles.first_name',
            'jobseeker_profiles.last_name',
            'jobseeker_profiles.zipcode',
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
            'jobseeker_profiles.is_completed',
            'jobseeker_profiles.is_job_seeker_verified',
            'jobseeker_profiles.about_me')
            ->leftjoin('job_titles', 'job_titles.id', 'jobseeker_profiles.job_titile_id')
            ->leftjoin('preferred_job_locations', 'preferred_job_locations.id', 'jobseeker_profiles.preferred_job_location_id')
            ->where('jobseeker_profiles.user_id', $userId)
            ->first();

        if ($userModel) {
            $return = $userModel->toArray();
            $return['profile_pic'] = ApiResponse::getThumbImage($return['profile_pic']);
            if (($return['dental_state_board']) && $return['dental_state_board'] != "") {
                $return['dental_state_board'] = ApiResponse::getThumbImage($return['dental_state_board']);
            } else {
                $return['dental_state_board'] = "";
            }

        }
        return $return;
    }

    public static function getAvailability($userId, $calendarStartDate, $calendarEndDate)
    {
        $list = ['calendarAvailability' => [], 'tempDatesAvailability' => []];
        $jobSeekerModel = static::select(['is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday',
                                          'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday'])
            ->where('user_id', $userId)->first();

        if ($jobSeekerModel) {
            $list['calendarAvailability'] = $jobSeekerModel->toArray();
            $tempAvailability = JobSeekerTempAvailability::select('temp_job_date')
                ->where('user_id', $userId)
                ->whereBetween('temp_job_date', [$calendarStartDate, $calendarEndDate])
                ->get();
            if ($tempAvailability) {
                foreach ($tempAvailability as $value) {
                    $list['tempDatesAvailability'][] = $value['temp_job_date'];
                }
            }
        }
        return $list;
    }

    public static function checkIfAvailabilitySet($userId)
    {
        $checkAvailabilityStatus = 0;
        $userAvailability = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select('is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday',
                'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday')
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->where('users.id', $userId)
            ->first();
        if ($userAvailability) {
            $statusAvailability = $userAvailability->is_fulltime || $userAvailability->is_parttime_monday || $userAvailability->is_parttime_tuesday || $userAvailability->is_parttime_wednesday
                || $userAvailability->is_parttime_thursday || $userAvailability->is_parttime_friday || $userAvailability->is_parttime_saturday || $userAvailability->is_parttime_sunday;
            $checkAvailabilityStatus = (int)(!empty($statusAvailability) ? 1 : 0);

        }

        $tempAvailableUsers = JobSeekerTempAvailability::where('user_id', $userId)->get()->count();
        if ($tempAvailableUsers > 0) {
            $checkAvailabilityStatus = 1;
        }
        return $checkAvailabilityStatus;
    }

}
