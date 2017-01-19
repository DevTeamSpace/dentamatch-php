<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;

class UserProfileController extends Controller {

    public function createProfile(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'officeName' => 'required',
                        'officeDescription' => 'required',
                        'officeType' => 'required',
                        'postal_code' => 'required',
                        'officeAddress' => 'required',
                        'phoneNumber' => 'required|numeric|digits_between:9,10',
            ], $this->messages());

            if ($validator->fails()) {
                return redirect('home')
                                ->withErrors($validator)
                                ->withInput();
            }

//            print_r($request->all());exit;
            \App\Models\RecruiterOffice::create([
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
                'sunday_end' => $request->sundayEnd
            ]);
            return redirect('home');
        } catch (\Exception $e) {
            return view('web.dashboard.', ["message" => $e->getMessage()]);
        }
    }

    public function messages() {
        return [
            'postal_code.required' => trans("messages.address_zip_required")
        ];
    }

}
