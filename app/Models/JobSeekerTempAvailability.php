<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

/**
 * App\Models\JobSeekerTempAvailability
 *
 * @property int $id
 * @property int $user_id
 * @property string $temp_job_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerTempAvailability onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability whereTempJobDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\JobSeekerTempAvailability whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerTempAvailability withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JobSeekerTempAvailability withoutTrashed()
 * @mixin \Eloquent
 */
class JobSeekerTempAvailability extends Model
{
    use SoftDeletes;
  
    protected $table  = 'jobseeker_temp_availability';
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
    
    public static function addTempDateAvailability($userId, $currentDate, $endDate) {
        $tempDateArray = [];
        $dayArray = [1,2,3,4,5];
        while(count($tempDateArray) < 23) {
                $dateString = (int) date("w", $currentDate);
                $insertDate = date( "Y-m-d",$currentDate);
                if($dateString!=6 && $dateString!=0) {
                    Log::info($dateString);
                    Log::info($insertDate);
                    $tempDateArray[] = array('user_id' => $userId, 'temp_job_date' => $insertDate);
                }
                $currentDate = strtotime($insertDate." +1 days");
        }
        self::insert($tempDateArray);
    }
    
    
}
