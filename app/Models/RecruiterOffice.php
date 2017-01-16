<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterOffice extends Model {

    protected $table = 'recruiter_offices';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'address', 'zipcode', 'latitude', 'longitude', 'phone_no', 'office_info', 'work_everyday_start', 'work_everyday_end', 'monday_start', 'monday_end', 'tuesday_start', 'tuesday_end', 'wednesday_start', 'wednesday_end', 'thursday_start', 'thursday_end', 'friday_start', 'friday_end', 'saturday_start', 'saturday_end', 'sunday_start', 'sunday_end', 'office_location'];

    
    public static function getAllRecruiterOffices($userId){
        return RecruiterOffice::leftJoin('locations','locations.zipcode','=','recruiter_offices.zipcode')
                ->where('user_id',$userId)
                ->select('recruiter_offices.id','recruiter_offices.address','locations.zipcode')
                ->get()->toArray();
    }
}
