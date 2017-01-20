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
                    'phone_no' => $request->phoneNumber,
                    'office_info' => $request->officeDescription,
                    'work_everyday_start' => $request->everydayStart,
                    'work_everyday_end' => $request->everydayEnd,
                    'monday_start' => $request->mondayStart,
                    'monday_end' => $request->mondayEnd,
                    'tuesday_start' => $request->tuesdayStart,
                    'tuesday_end' => $request->tuesdayEnd,
                    'wednesday_start' => $request->wednesdayStart,
                    'wednesday_end' => $request->wednesdayEnd,
                    'thursday_start' => $request->thrusdayStart,
                    'thursday_end' => $request->thrusdayEnd,
                    'friday_start' => $request->fridayStart,
                    'friday_end' => $request->fridayEnd,
                    'saturday_start' => $request->saturdayStart,
                    'saturday_end' => $request->saturdayEnd,
                    'sunday_start' => $request->sundayStart,
                    'sunday_end' => $request->sundayEnd,
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
