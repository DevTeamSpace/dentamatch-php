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
use App\Http\Requests\EditRecruiterOfficeRequest;
use App\Models\OfficeType;
use App\Http\Requests\DeleteOfficeRequest;
use App\Http\Requests\UpdateRecruiterProfileRequest;
use Hash;
use Log;

class UserProfileController extends Controller {
    private $result = [];
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
            $recOfficeArrObj = RecruiterOffice::createProfile($request);
            return ['success'=>1,'data'=>$recOfficeArrObj];
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
            $profile = RecruiterProfile::updateOfficeDetail($request);
            $request->session()->put('userData.profile.office_name', $request->officeName);
                
            return 'success';
        } catch (\Exception $e) {
            return 'fail';
        }
    }

    public function getChangePassword() {
        return view('web.change_password',['activeTab'=>'3']);
    }
    
    public function getTermsConditions() {
        return view('web.setting-terms-conditions',['activeTab'=>'5']);
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
            return redirect('change-password')->withErrors([trans("messages.password_saved_successfully")])->withInput();
        }
        return redirect('change-password')->withErrors([trans("messages.old_not_match")])->withInput();
    }

    public function getEditProfile() {
        return view('web.edit_profile', ['activeTab'=>'3']);
    }
    
    public function getRecruiterProfileDetails(){
        try{
            $user = RecruiterProfile::where('user_id', Auth::user()->id)->select('id', 'user_id', 'office_name', 'office_desc')->first();
            $officeType = \App\Models\OfficeType::all();
            $offices = RecruiterOffice::join('recruiter_office_types', 'recruiter_office_types.recruiter_office_id', '=', 'recruiter_offices.id')
                            ->join('office_types', 'recruiter_office_types.office_type_id', '=', 'office_types.id')
                            ->where('user_id', Auth::user()->id)
                            ->select('recruiter_offices.*', DB::raw('group_concat(office_types.officetype_name) as officetype_names'), DB::raw('group_concat(office_types.id) as officetype_id'))
                            ->groupby('recruiter_offices.id')->get();
            $this->result['user'] = $user;
            $this->result['officeType'] = $officeType;
            $this->result['offices'] = $offices;
            $this->result['success'] = true;
        } catch (\Exception $e) {
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
    public function postEditRecruiterOffice(EditRecruiterOfficeRequest $request){
        try{
            DB::beginTransaction();
            $allData = json_decode($request->officeDetails);
            
            $recruiterOfficeObj = RecruiterOffice::where(['id' => (int)$request->officeId])->first();
            
            if($request->new == "true"){
                $recruiterOfficeObj = new RecruiterOffice();
            }
            
            $recruiterOfficeObj->user_id = Auth::user()->id;
            $recruiterOfficeObj->phone_no = $allData->officePhone;
            $recruiterOfficeObj->work_everyday_start = ($allData->officeWorkingHours->isEverydayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->everydayStart)) : '';
            $recruiterOfficeObj->work_everyday_end = ($allData->officeWorkingHours->isEverydayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->everydayEnd)) : '';
            $recruiterOfficeObj->monday_start = ($allData->officeWorkingHours->isMondayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->mondayStart)) : '';
            $recruiterOfficeObj->monday_end = ($allData->officeWorkingHours->isMondayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->mondayEnd)) : '';
            $recruiterOfficeObj->tuesday_start = ($allData->officeWorkingHours->isTuesdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->tuesdayStart)) : '';
            $recruiterOfficeObj->tuesday_end = ($allData->officeWorkingHours->isTuesdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->tuesdayEnd)) : '';
            $recruiterOfficeObj->wednesday_start = ($allData->officeWorkingHours->isWednesdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->wednesdayStart)) : '';
            $recruiterOfficeObj->wednesday_end = ($allData->officeWorkingHours->isWednesdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->wednesdayEnd)) : '';
            $recruiterOfficeObj->thursday_start = ($allData->officeWorkingHours->isThursdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->thursdayStart)) : '';
            $recruiterOfficeObj->thursday_end = ($allData->officeWorkingHours->isThursdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->thursdayEnd)) : '';
            $recruiterOfficeObj->friday_start = ($allData->officeWorkingHours->isFridayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->fridayStart)) : '';
            $recruiterOfficeObj->friday_end = ($allData->officeWorkingHours->isFridayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->fridayEnd)) : '';
            $recruiterOfficeObj->saturday_start = ($allData->officeWorkingHours->isSaturdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->saturdayStart)) : '';
            $recruiterOfficeObj->saturday_end = ($allData->officeWorkingHours->isSaturdayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->saturdayEnd)) : '';
            $recruiterOfficeObj->sunday_start = ($allData->officeWorkingHours->isSundayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->sundayStart)) : '';
            $recruiterOfficeObj->sunday_end = ($allData->officeWorkingHours->isSundayWork == true) ? date('H:i:s', strtotime($allData->officeWorkingHours->sundayEnd)) : '';
            
            $checkRecruiterOfficeExistence = RecruiterOffice::where([
                'latitude' => (string)$allData->officeLat,
                'longitude' => (string)$allData->officeLng,
                'user_id' => Auth::user()->id
                    ])
                ->where('id', '!=' ,$request->officeId)->first();
            if($checkRecruiterOfficeExistence != null){
                $this->result['success'] = false;
                $this->result['message'] = trans('messages.address_already_associated');
            }else{
                $recruiterOfficeObj->address = $allData->officeAddress;
                $recruiterOfficeObj->office_info = $allData->officeInfo;
                $recruiterOfficeObj->zipcode = (int)$allData->officeZipcode;
                $recruiterOfficeObj->latitude = $allData->officeLat;
                $recruiterOfficeObj->longitude = $allData->officeLng;
                $recruiterOfficeObj->save();
                
                $newOfficeType = $this->addOfficeType($recruiterOfficeObj, $allData, $request->new);
                DB::commit();
                $this->result['recruiterOffice'] = $recruiterOfficeObj;
                $this->result['recruiterOfficeType'] = $newOfficeType['data'];
                $this->result['success'] = true;
                $this->result['message'] = trans('messages.office_updated');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
    private function addOfficeType($office, $allData, $new){
        try{
            if($new != "true"){
                RecruiterOfficeType::where(['recruiter_office_id' => $office['id']])->delete();
            }
            $allRecruiterOfficeType = OfficeType::get();
            $newOfficeType = [];
            foreach($allRecruiterOfficeType as $officeType){
                if(in_array($officeType['officetype_name'], $allData->officeType)){
                    $newRecruiterOfficeType = new RecruiterOfficeType();
                    $newRecruiterOfficeType->recruiter_office_id = $office['id'];
                    $newRecruiterOfficeType->office_type_id = $officeType['id'];
                    $newRecruiterOfficeType->save();
                    array_push($newOfficeType, $newRecruiterOfficeType);
                }
            }
            $this->result['success'] = false;
            $this->result['data'] = $newOfficeType;
        } catch (\Exception $e) {
            Log::error($e);
            $this->result['message'] = $e->getMessage();
        }
        $this->result;
    }

    public function postDeleteOffice(DeleteOfficeRequest $request){
        try {
            $recruiterOffice = RecruiterOffice::where('id',$request->officeId)->where('user_id',Auth::id())->first();
            
            if($recruiterOffice){
                RecruiterOfficeType::where('recruiter_office_id',(int)$request->officeId)->delete();
                $recruiterOffice->delete();   
            }
            $this->result['success'] = true;
        } catch (\Exception $e) {
            Log::error($e);
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
    public function postUpdateRecruiterProfile(UpdateRecruiterProfileRequest $request){
        try {
            $recruiterProfile = RecruiterProfile::where('id',$request->profileId)->where('user_id',Auth::id())->first();
            if($recruiterProfile){
                $recruiterProfile->office_name = $request->officeName;
                $recruiterProfile->office_desc = $request->officeDescription;
                $recruiterProfile->save();
            }
            $this->result['success'] = true;
        } catch (\Exception $e) {
            Log::error($e);
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
}
