<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\RecruiterOffice;
use DB;
use App\Models\RecruiterProfile;
use App\Models\RecruiterOfficeType;
use Hash;

class UserProfileController extends Controller {

    public function officeDetails(Request $request) {

        if (isset($request->phoneNumber) && !empty($request->phoneNumber)) {
            $var = filter_var($request->phoneNumber, FILTER_SANITIZE_NUMBER_INT);
            $newPhone = str_replace(array('+', '-'), '', $var);
            $request->merge(array('contactNumber' => $newPhone));
        }

        $this->validate($request, [
            'officeType' => 'required',
            'postal_code' => 'required',
            'officeAddress' => 'required',
            'everydayStart' => 'required_if:everyday,1',
            'everydayEnd' => 'required_if:everyday,1',
            'mondayStart' => 'required_if:monday,1',
            'mondayEnd' => 'required_if:monday,1',
            'tuesdayStart' => 'required_if:tuesday,1',
            'tuesdayEnd' => 'required_if:tuesday,1',
            'wednesdayStart' => 'required_if:wednesday,1',
            'wednesdayEnd' => 'required_if:wednesday,1',
            'thrusdayStart' => 'required_if:thrusday,1',
            'thrusdayEnd' => 'required_if:thrusday,1',
            'fridayStart' => 'required_if:friday,1',
            'fridayEnd' => 'required_if:friday,1',
            'saturdayStart' => 'required_if:saturday,1',
            'saturdayEnd' => 'required_if:saturday,1',
            'sundayStart' => 'required_if:sunday,1',
            'sundayEnd' => 'required_if:sunday,1',
            'contactNumber' => 'required|numeric|digits_between:9,10'
        ]);

        try {
            RecruiterOffice::createProfile($request);
//            if (in_array($request->postal_code, \App\Models\Location::getList())) {
            return 'success';
//            }
//            return 1;
        } catch (\Exception $e) {
            return 'fail';
        }
    }

    public function checkValidLocation(Request $request) {
        try {
            if (isset($request->zip) && !empty($request->zip)) {
                if (in_array($request->zip, \App\Models\Location::getList())) {
                    return 1;
                }
                return 2;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function createProfile(Request $request) {
        $this->validate($request, ['officeName' => 'required|max:100', 'officeDescription' => 'required|max:500']);

        try {
            RecruiterProfile::updateOfficeDetail($request);
            return 'success';
            //return redirect('jobtemplates');
        } catch (\Exception $e) {
            return 'fail';
//            return view('web.dashboard.', ["message" => $e->getMessage()]);
        }
    }

//    public function messages() {
//        return [
//            'postal_code.required' => trans("messages.address_zip_required")
//        ];
//    }

    public function getChangePassword() {
        return view('web.change_password');
    }

    public function postChangePassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'oldPassword' => 'required',
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return redirect('change-password')->withErrors($validator)->withInput();
        }
        if (Hash::check($request->oldPassword, Auth::user()->password)) {
            \App\Models\User::where('id', Auth::user()->id)->update(['password' => Hash::make($request->password)]);
            return redirect('change-password')->withErrors(['Changed Successfull.'])->withInput();
        }
        return redirect('change-password')->withErrors(['Old Password not matched.'])->withInput();
    }

    public function getEditProfile() {
        $user = RecruiterProfile::where('user_id', Auth::user()->id)->first();
        $officeType = \App\Models\OfficeType::all();
        $offices = RecruiterOffice::join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
                        ->join('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
                        ->where('user_id', Auth::user()->id)
                        ->select('recruiter_offices.*', DB::raw('group_concat(office_types.officetype_name) as officetype_names'), DB::raw('group_concat(office_types.id) as officetype_id'))
                        ->groupby('recruiter_offices.id')->get();
        return view('web.edit_profile', ['user' => $user, 'offices' => $offices, 'officeType' => $officeType]);
    }

}
