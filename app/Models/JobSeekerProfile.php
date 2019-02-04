<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * App\Models\JobSeekerProfile
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
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $preferred_city
 * @property string|null $preferred_state
 * @property string|null $preferred_country
 * @property-read string|null $mapping_for
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereAboutMe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereDentalStateBoard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsFulltime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsJobSeekerVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeFriday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeMonday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeSaturday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeSunday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeThursday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeTuesday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereIsParttimeWednesday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereJobTitileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile wherePreferredCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile wherePreferredCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile wherePreferredJobLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile wherePreferredJobLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile wherePreferredState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereProfilePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereSignupSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerProfile whereZipcode($value)
 * @mixin \Eloquent
 */
class JobSeekerProfile extends Model {

    use Eloquence,
        Mappable;

    protected $table = 'jobseeker_profiles';
    protected $primaryKey = 'id';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'first_name', 'last_name', 'profile_pic', 'zipcode', 'latitude', 'longitude', 'preferred_job_location', 'job_titile_id', 'dental_state_board', 'license_number', 'state', 'about_me', 'verification_code', 'is_verified', 'is_completed', 'is_fulltime', 'is_parttime_monday', 'is_parttime_tuesday', 'is_parttime_wednesday', 'is_parttime_thursday', 'is_parttime_friday', 'is_parttime_saturday', 'is_parttime_sunday'];

}
