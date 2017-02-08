<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RecruiterOffice extends Model {

    protected $table = 'recruiter_offices';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'address', 'zipcode', 'latitude', 'longitude', 'phone_no', 'office_info', 'work_everyday_start', 'work_everyday_end', 'monday_start', 'monday_end', 'tuesday_start', 'tuesday_end', 'wednesday_start', 'wednesday_end', 'thursday_start', 'thursday_end', 'friday_start', 'friday_end', 'saturday_start', 'saturday_end', 'sunday_start', 'sunday_end', 'office_location'];

    public function officeTypes() {
        return $this->hasMany(RecruiterOfficeType::class, 'recruiter_office_id');
    }

    public static function getAllRecruiterOffices($userId) {
        return RecruiterOffice::leftJoin('locations', 'locations.zipcode', '=', 'recruiter_offices.zipcode')
                        ->where('user_id', $userId)
                        ->select('recruiter_offices.id', 'recruiter_offices.address', 'locations.zipcode')
                        ->get()->toArray();
    }

    public static function createProfile($request) {

        if (isset($request->editId) && !empty($request->editId)) {
            $recOfficeArrObj = RecruiterOffice::find($request->editId);
            
            if($recOfficeArrObj->user_id!=Auth::user()->id){
                return true;
            }

            $recOfficeArrObj->user_id = Auth::user()->id;
            $recOfficeArrObj->address = $request->officeAddress;
            $recOfficeArrObj->zipcode = $request->postal_code;
            $recOfficeArrObj->latitude = $request->lat;
            $recOfficeArrObj->longitude = $request->lng;
            $recOfficeArrObj->phone_no = $request->contactNumber;
            $recOfficeArrObj->office_info = $request->officeAddress;
            $recOfficeArrObj->work_everyday_start = ($request->everydayStart != '') ? date('H:i:s', strtotime($request->everydayStart)) : null;
            $recOfficeArrObj->work_everyday_end = ($request->everydayEnd != '') ? date('H:i:s', strtotime($request->everydayEnd)) : null;
            $recOfficeArrObj->monday_start = ($request->mondayStart != '') ? date('H:i:s', strtotime($request->mondayStart)) : null;
            $recOfficeArrObj->monday_end = ($request->mondayEnd != '') ? date('H:i:s', strtotime($request->mondayEnd)) : null;
            $recOfficeArrObj->tuesday_start = ($request->tuesdayStart != '') ? date('H:i:s', strtotime($request->tuesdayStart)) : null;
            $recOfficeArrObj->tuesday_end = ($request->tuesdayEnd != '') ? date('H:i:s', strtotime($request->tuesdayEnd)) : null;
            $recOfficeArrObj->wednesday_start = ($request->wednesdayStart != '') ? date('H:i:s', strtotime($request->wednesdayStart)) : null;
            $recOfficeArrObj->wednesday_end = ($request->wednesdayEnd != '') ? date('H:i:s', strtotime($request->wednesdayEnd)) : null;
            $recOfficeArrObj->thursday_start = ($request->thrusdayEnd != '') ? date('H:i:s', strtotime($request->thrusdayEnd)) : null;
            $recOfficeArrObj->thursday_end = ($request->thrusdayEnd != '') ? date('H:i:s', strtotime($request->thrusdayEnd)) : null;
            $recOfficeArrObj->friday_start = ($request->fridayStart != '') ? date('H:i:s', strtotime($request->fridayStart)) : null;
            $recOfficeArrObj->friday_end = ($request->fridayEnd != '') ? date('H:i:s', strtotime($request->fridayEnd)) : null;
            $recOfficeArrObj->saturday_start = ($request->saturdayStart != '') ? date('H:i:s', strtotime($request->saturdayStart)) : null;
            $recOfficeArrObj->saturday_end = ($request->saturdayEnd != '') ? date('H:i:s', strtotime($request->saturdayEnd)) : null;
            $recOfficeArrObj->sunday_start = ($request->sundayStart != '') ? date('H:i:s', strtotime($request->sundayStart)) : null;
            $recOfficeArrObj->sunday_end = ($request->sundayEnd != '') ? date('H:i:s', strtotime($request->sundayEnd)) : null;
            $recOfficeArrObj->office_location = $request->officeLocation;
            $recOfficeArrObj->save();
        } else {
            $recOfficeArrObj = RecruiterOffice::create([
                        'user_id' => Auth::user()->id,
                        'address' => $request->officeAddress,
                        'zipcode' => $request->postal_code,
                        'latitude' => $request->lat,
                        'longitude' => $request->lng,
                        'phone_no' => $request->contactNumber,
                        'office_info' => $request->officeAddress,
                        'work_everyday_start' => ($request->everydayStart != '') ? date('H:i:s', strtotime($request->everydayStart)) : null,
                        'work_everyday_end' => ($request->everydayEnd != '') ? date('H:i:s', strtotime($request->everydayEnd)) : null,
                        'monday_start' => ($request->mondayStart != '') ? date('H:i:s', strtotime($request->mondayStart)) : null,
                        'monday_end' => ($request->mondayEnd != '') ? date('H:i:s', strtotime($request->mondayEnd)) : null,
                        'tuesday_start' => ($request->tuesdayStart != '') ? date('H:i:s', strtotime($request->tuesdayStart)) : null,
                        'tuesday_end' => ($request->tuesdayEnd != '') ? date('H:i:s', strtotime($request->tuesdayEnd)) : null,
                        'wednesday_start' => ($request->wednesdayStart != '') ? date('H:i:s', strtotime($request->wednesdayStart)) : null,
                        'wednesday_end' => ($request->wednesdayEnd != '') ? date('H:i:s', strtotime($request->wednesdayEnd)) : null,
                        'thursday_start' => ($request->thrusdayStart != '') ? date('H:i:s', strtotime($request->thrusdayStart)) : null,
                        'thursday_end' => ($request->thrusdayEnd != '') ? date('H:i:s', strtotime($request->thrusdayEnd)) : null,
                        'friday_start' => ($request->fridayStart != '') ? date('H:i:s', strtotime($request->fridayStart)) : null,
                        'friday_end' => ($request->fridayEnd != '') ? date('H:i:s', strtotime($request->fridayEnd)) : null,
                        'saturday_start' => ($request->saturdayStart != '') ? date('H:i:s', strtotime($request->saturdayStart)) : null,
                        'saturday_end' => ($request->saturdayEnd != '') ? date('H:i:s', strtotime($request->saturdayEnd)) : null,
                        'sunday_start' => ($request->sundayStart != '') ? date('H:i:s', strtotime($request->sundayStart)) : null,
                        'sunday_end' => ($request->sundayEnd != '') ? date('H:i:s', strtotime($request->sundayEnd)) : null,
                        'office_location' => $request->officeLocation
            ]);
        }
        $recOfficeTypeArrObj = [];
        foreach ($request->officeType as $type) {
            $recOfficeTypeArrObj[] = new RecruiterOfficeType(['office_type_id' => (int) $type]);
        }
        if (!empty($recOfficeTypeArrObj)) {
            if (isset($request->editId) && !empty($request->editId)) {
                RecruiterOfficeType::where('recruiter_office_id',$request->editId)->delete();
            }
            $recOfficeArrObj->officeTypes()->saveMany($recOfficeTypeArrObj);
        }
    }
    
    public static function getAllOffices(){
        return RecruiterOffice::join(
                'locations', 'recruiter_offices.zipcode', '=', 'locations.zipcode'
                )->where(['user_id' => Auth::user()->id, 'locations.is_active' => config('constants.LocationActive')])
                ->get()
                ->toArray();
    }

}
