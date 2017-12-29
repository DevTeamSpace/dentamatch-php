<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

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
        while($currentDate<=$endDate) {
                $date = date("l", $currentDate);
                $date = strtolower($date);
                $insertDate = date( "Y-m-d", $currentDate );
                if($date != "saturday" || $date != "sunday") {
                    $tempDateArray[] = array('user_id' => $userId, 'temp_job_date' => $insertDate);
                }
                $currentDate = strtotime($insertDate." +1 days");
        }
        self::insert($tempDateArray);
    }
    
    
}
