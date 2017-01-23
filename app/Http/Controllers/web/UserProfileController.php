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

class UserProfileController extends Controller {

    public function createProfile(Request $request) {

        $validator = Validator::make($request->all(), [
                    'officeName' => 'required|max:100',
                    'officeDescription' => 'required|max:500',
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
//print_r($request->all());exit;
        try {
            DB::beginTransaction();
            RecruiterProfile::updateOfficeDetail($request);
            RecruiterOffice::createProfile($request);
            DB::commit();
            return redirect('jobtemplates');
        } catch (\Exception $e) {
            DB::rollback();
            return view('web.dashboard.', ["message" => $e->getMessage()]);
        }
    }

    public function messages() {
        return [
            'postal_code.required' => trans("messages.address_zip_required")
        ];
    }

}
