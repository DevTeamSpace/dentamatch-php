<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        $insertTempDateArray = [];
        while( $currentDate <= $endDate ) {
                $insertTempDateArray[] = date( "Y-m-d", $currentDate );
                $currentDate = strtotime("+1", $currentDate );
        }
        if(!empty($insertTempDateArray)) {
            foreach($insertTempDateArray as $newTempDate) {     
                    $tempDateArray[] = array('user_id' => $userId, 'temp_job_date' => $newTempDate);
            }
            self::insert($tempDateArray);
        }
    }
    
    
}
