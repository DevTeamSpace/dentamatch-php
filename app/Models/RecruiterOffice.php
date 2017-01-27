<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecruiterOffice extends Model {

    protected $table = 'recruiter_offices';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'address', 'zipcode', 'latitude', 'longitude', 'phone_no', 'office_info', 'work_everyday_start', 'work_everyday_end', 'monday_start', 'monday_end', 'tuesday_start', 'tuesday_end', 'wednesday_start', 'wednesday_end', 'thursday_start', 'thursday_end', 'friday_start', 'friday_end', 'saturday_start', 'saturday_end', 'sunday_start', 'sunday_end', 'office_location'];

    public function officeTypes(){
        return $this->hasMany(RecruiterOfficeType::class,'recruiter_office_id');
    }
    
    public static function getAllRecruiterOffices($userId) {
        return RecruiterOffice::leftJoin('locations', 'locations.zipcode', '=', 'recruiter_offices.zipcode')
                        ->where('user_id', $userId)
                        ->select('recruiter_offices.id', 'recruiter_offices.address', 'locations.zipcode')
                        ->get()->toArray();
    }

    public static function createProfile($request) {
        $recOfficeArrObj =  RecruiterOffice::create([
                    'user_id' => Auth::user()->id,
                    'address' => $request->officeAddress,
                    'zipcode' => $request->postal_code,
                    'latitude' => $request->lat,
                    'longitude' => $request->lng,
                    'phone_no' => $request->contactNumber,
                    'office_info' => $request->officeDescription,
                    'work_everyday_start' => date('H:i:s', strtotime($request->everydayStart)),
                    'work_everyday_end' => date('H:i:s', strtotime($request->everydayEnd)),
                    'monday_start' => date('H:i:s', strtotime($request->mondayStart)),
                    'monday_end' => date('H:i:s', strtotime($request->mondayEnd)),
                    'tuesday_start' => date('H:i:s', strtotime($request->tuesdayStart)),
                    'tuesday_end' => date('H:i:s', strtotime($request->tuesdayEnd)),
                    'wednesday_start' => date('H:i:s', strtotime($request->wednesdayStart)),
                    'wednesday_end' => date('H:i:s', strtotime($request->wednesdayEnd)),
                    'thursday_start' => date('H:i:s', strtotime($request->thrusdayStart)),
                    'thursday_end' => date('H:i:s', strtotime($request->thrusdayEnd)),
                    'friday_start' => date('H:i:s', strtotime($request->fridayStart)),
                    'friday_end' => date('H:i:s', strtotime($request->fridayEnd)),
                    'saturday_start' => date('H:i:s', strtotime($request->saturdayStart)),
                    'saturday_end' => date('H:i:s', strtotime($request->saturdayEnd)),
                    'sunday_start' => date('H:i:s', strtotime($request->sundayStart)),
                    'sunday_end' => date('H:i:s', strtotime($request->sundayEnd)),
                    'office_location' => $request->officeLocation
        ]);
        $recOfficeTypeArrObj = [];
        foreach ($request->officeType as $type) {
            $recOfficeTypeArrObj[] = new RecruiterOfficeType(['office_type_id' => (int)$type]);
        }
        if(!empty($recOfficeTypeArrObj)){
            $recOfficeArrObj->officeTypes()->saveMany($recOfficeTypeArrObj);
        }
    }

}
