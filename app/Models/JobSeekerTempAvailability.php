<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon as IlluminateCarbon;
use Log;

/**
 * App\Models\JobSeekerTempAvailability
 *
 * @property int $id
 * @property int $user_id
 * @property string $temp_job_date
 * @property IlluminateCarbon $created_at
 * @property IlluminateCarbon $updated_at
 * @property IlluminateCarbon|null $deleted_at
 * @property-read User $seeker
 *
 * @method static bool|null forceDelete()
 * @method static Builder|JobSeekerTempAvailability newModelQuery()
 * @method static Builder|JobSeekerTempAvailability newQuery()
 * @method static QueryBuilder|JobSeekerTempAvailability onlyTrashed()
 * @method static Builder|JobSeekerTempAvailability query()
 * @method static bool|null restore()
 * @method static Builder|JobSeekerTempAvailability whereCreatedAt($value)
 * @method static Builder|JobSeekerTempAvailability whereDeletedAt($value)
 * @method static Builder|JobSeekerTempAvailability whereId($value)
 * @method static Builder|JobSeekerTempAvailability whereTempJobDate($value)
 * @method static Builder|JobSeekerTempAvailability whereUpdatedAt($value)
 * @method static Builder|JobSeekerTempAvailability whereUserId($value)
 * @method static QueryBuilder|JobSeekerTempAvailability withTrashed()
 * @method static QueryBuilder|JobSeekerTempAvailability withoutTrashed()
 * @mixin \Eloquent
 */
class JobSeekerTempAvailability extends Model
{
    use SoftDeletes;

    protected $table = 'jobseeker_temp_availability';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at', 'deleted_at', 'created_at'
    ];

    public function seeker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function addTempDateAvailability($userId, $currentDate, $endDate)
    {
        $tempDateArray = [];
        while (count($tempDateArray) < 23) {
            $dateString = (int)date("w", $currentDate);
            $insertDate = date("Y-m-d", $currentDate);
            if ($dateString != Carbon::SATURDAY && $dateString != Carbon::SUNDAY) {
                Log::info($dateString);
                Log::info($insertDate);
                $tempDateArray[] = ['user_id' => $userId, 'temp_job_date' => $insertDate];
            }
            $currentDate = strtotime($insertDate . " +1 days");
        }
        self::insert($tempDateArray);
    }

}
