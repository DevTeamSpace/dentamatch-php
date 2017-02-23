<?php

namespace App\Http\Controllers\web;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecruiterJobs;
use App\Models\JobTemplates;
use App\Models\TempJobDates;
use App\Models\TemplateSkills;
use App\Models\JobLists;
use App\Models\JobTitles;
use App\Models\RecruiterOffice;
use App\Models\ChatUserLists;
use App\Models\JobSeekerProfiles;
use App\Providers\NotificationServiceProvider;
use App\Models\Device;
use App\Models\Notification;
use App\Models\RecruiterProfile;
use App\Models\OfficeType;
use App\Models\RecruiterOfficeType;
use DB;

class RecruiterJobController extends Controller {

    protected $user, $viewData, $result;

    public function __construct() {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->viewData = ['navActive' => 'joblisting',];
    }

    public function returnView($viewFileName) {
        return view('web.recuriterJob.' . $viewFileName, $this->viewData);
    }

    public function createJob($templateId) {
        try {
            $this->viewData['offices'] = RecruiterOffice::getAllRecruiterOffices(Auth::user()->id);
            $this->viewData['templateId'] = $templateId;
            $this->viewData['jobTemplates'] = JobTemplates::findById($templateId);
            ;

            return $this->returnView('create');
        } catch (\Exception $e) {
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function searchSeekers(Request $request, $jobId) {
        try {
            $searchData = $request->all();

            $distance = $request->get('distance');
            $availAll = $request->get('avail_all');
            if (empty($distance))
                $distance = JobSeekerProfiles::DISTANCE;
            if (empty($availAll)) {
                $availAll = 0;
            }
            $searchData['distance'] = $distance;
            $searchData['avail_all'] = $availAll;
            $jobDetails = RecruiterJobs::getRecruiterJobDetails($jobId);
            $seekersList = JobSeekerProfiles::getJobSeekerProfiles($jobDetails, $searchData);
            //dd($seekersList['paginate']);

            if ($request->ajax()) {
                return view('web.recuriterJob.search', ['seekersList' => $seekersList, 'jobDetails' => $jobDetails, 'searchData' => $searchData])->render();
            }

            return view('web.recuriterJob.search', compact('seekersList', 'jobDetails', 'searchData'));
        } catch (\Exception $e) {
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function saveOrUpdate(Request $request) {
        $this->validate($request, [
            'templateId' => 'required',
            'dentalOfficeId' => 'required|integer',
            'jobType' => 'required|in:1,2,3',
            'partTimeDays' => 'required_if:jobType,2',
            'tempDates' => 'required_if:jobType,3',
            'noOfJobs' => 'required_if:jobType,3',
            'action' => 'required|in:add,edit',
            'id' => 'integer|required_if:action,edit'
        ]);
        try {

            DB::beginTransaction();
            $recruiterJobObj = new RecruiterJobs();
            if ($request->action == "edit" && !empty($request->id)) {
                $recruiterJobObj = RecruiterJobs::findById($request->id);
            }

            $recruiterJobObj->job_template_id = $request->templateId;
            $recruiterJobObj->recruiter_office_id = $request->dentalOfficeId;
            $recruiterJobObj->job_type = $request->jobType;
            $recruiterJobObj->no_of_jobs = ($request->noOfJobs != '') ? $request->noOfJobs : 0;

            if ($request->jobType == RecruiterJobs::PARTTIME) {
                $recruiterJobObj->is_monday = in_array('1', $request->partTimeDays);
                $recruiterJobObj->is_tuesday = in_array('2', $request->partTimeDays);
                $recruiterJobObj->is_wednesday = in_array('3', $request->partTimeDays);
                $recruiterJobObj->is_thursday = in_array('4', $request->partTimeDays);
                $recruiterJobObj->is_friday = in_array('5', $request->partTimeDays);
                $recruiterJobObj->is_saturday = in_array('6', $request->partTimeDays);
                $recruiterJobObj->is_sunday = in_array('7', $request->partTimeDays);
            }
            $recruiterJobObj->save();
            if ($request->jobType == RecruiterJobs::TEMPORARY) {
                $tempDates = explode(',', $request->tempDates);
                if (count($tempDates) > 0) {
                    $tempDateArrObj = [];
                    foreach ($tempDates as $tempDate) {
                        $tempDateArrObj[] = new TempJobDates(['jobDate' => date('Y-m-d', strtotime($tempDate))]);
                    }
                    $recruiterJobObj->tempJobDates()->saveMany($tempDateArrObj);
                    unset($tempDateArrObj);
                }
            }
            DB::commit();
            unset($recruiterJobObj);
            return redirect('job/lists');
        } catch (\Exception $e) {
            DB::rollback();
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function listJobs(Request $request) {
        try {
            $this->viewData['jobList'] = RecruiterJobs::getJobs();

            if ($request->ajax()) {
                return view('web.recuriterJob.jobData', ['jobList' => $this->viewData['jobList']])->render();
            }

            return $this->returnView('list');
        } catch (\Exception $e) {
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function editJob(Request $request, $jobId) {
        try {
            dd('In progress');
        } catch (\Exception $e) {
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function jobDetails(Request $request, $jobId) {
        try {
            $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);
            $this->viewData['skills'] = TemplateSkills::getTemplateSkills($this->viewData['job']['job_template_id']);
            $this->viewData['seekerList'] = JobLists::getJobSeekerList($this->viewData['job']);
            return $this->returnView('view');
        } catch (\Exception $e) {
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request) {
        $this->validate($request, [
            'jobId' => 'required|integer',
            'seekerId' => 'required|integer',
            'appliedStatus' => 'required|integer',
        ]);
        try {
            $requestData = $request->all();
            $jobData = JobLists::getJobInfo($requestData['seekerId'], $requestData['jobId']);
            if ($jobData) {
                $jobData->applied_status = $requestData['appliedStatus'];
                $jobData->save();
                if ($requestData['appliedStatus'] == JobLists::SHORTLISTED || $requestData['appliedStatus'] == JobLists::HIRED) {
                    $userChat = new ChatUserLists();
                    $userChat->recruiter_id = Auth::id();
                    $userChat->seeker_id = $jobData->seeker_id;
                    $userChat->checkAndSaveUserToChatList();
                    $this->sendPushUser($requestData['appliedStatus'], Auth::user()->id, $jobData->seeker_id, $requestData['jobId']);
                }
                return redirect('job/details/' . $requestData['jobId']);
            } else {
                $inviteJobs = array('seeker_id' => $requestData['seekerId'], 'recruiter_job_id' => $requestData['jobId'], 'applied_status' => JobLists::INVITED);
                JobLists::insert($inviteJobs);
                $this->sendPushUser($requestData['appliedStatus'], Auth::user()->id, $requestData['seekerId'], $requestData['jobId']);
            }
        } catch (\Exception $e) {
            dd($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function jobSeekerDetails($seekerId, $jobId) {
        try {
            $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);

            $seekerDetails = JobSeekerProfiles::getJobSeekerDetails($seekerId, $this->viewData['job']);

            return view('web.recuriterJob.seekerdetails', compact('seekerDetails'));
        } catch (\Exception $e) {
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function sendPushUser($jobstatus, $sender, $receiverId, $jobId) {
        $jobDetails = RecruiterJobs::getRecruiterJobDetails($jobId);
        if ($jobstatus == JobLists::SHORTLISTED) {
            $notificationData = array(
                'notificationData' => $jobDetails['office_name'] . " has accepted your job application for " . $jobDetails['jobtitle_name'],
                'notification_title' => 'User shortlisted',
                'sender_id' => $sender,
                'type' => 1,
                'notificationType' => Notification::ACCEPTJOB,
            );
        } else if ($jobstatus == JobLists::HIRED) {
            $notificationData = array(
                'notificationData' => "You have been hired for  " . $jobDetails['jobtitle_name'],
                'notification_title' => 'User hired',
                'sender_id' => $sender,
                'type' => 1,
                'notificationType' => Notification::HIRED,
            );
        } else if ($jobstatus == JobLists::INVITED) {
            $notificationData = array(
                'notificationData' => $jobDetails['office_name'] . " has sent you a job invitation for " . $jobDetails['jobtitle_name'],
                'notification_title' => 'User invited',
                'sender_id' => $sender,
                'type' => 1,
                'notificationType' => Notification::INVITED,
            );
        }
        $data = ['receiver_id' => $receiverId, 'job_list_id' => $jobId, 'sender_id' => $sender, 'notification_data' => $notificationData['notificationData'], 'notification_type' => $jobstatus];
        $notificationDetails = Notification::create($data);
        $notificationData['id'] = $notificationDetails->id;
        $notificationData['receiverId'] = $receiverId;
        $notificationData['senderId'] = $sender;
        $params['data'] = $notificationData;
        $params['jobDetails'] = $jobDetails;
        $params['notification_details'] = $notificationDetails;
        $deviceModel = Device::getDeviceToken($receiverId);
        if ($deviceModel) {
            //$this->info($userId);
            NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['notificationData'], $params);
        }
    }

    public function jobEdit(Request $request, $jobId) {
        return view('web.recuriterJob.edit', compact('jobId'));
    }

    public function jobEditDetails(Request $request) {
        try {
            $allData = [];
            $jobDetails = RecruiterJobs::getRecruiterJobDetails($request->jobId);
            $jobSeekerStatus = JobLists::getJobSeekerStatus($request->jobId);
            $recruiterOffices = RecruiterOffice::getAllOffices();
            $allOfficeTypes = OfficeType::allOfficeTypes();
            $allData['jobDetails'] = $jobDetails;
            $allData['jobSeekerStatus'] = $jobSeekerStatus;
            $allData['recruiterOffices'] = $recruiterOffices;
            $allData['allOfficeTypes'] = $allOfficeTypes;
            $response = $allData;
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }

    public function postEditJob(Request $request) {
        try {
//            DB::beginTransaction();
            $allData = json_decode($request->jobDetails);
            if ($allData->selectedJobType == "Full Time") {
                $allData->selectedJobType = 1;
            } else if ($allData->selectedJobType == "Part Time") {
                $allData->selectedJobType = 2;
            } else {
                $allData->selectedJobType = 3;
            }
            $jobDetails = RecruiterJobs::getRecruiterJobDetails($allData->jobId);
            $recruiterOfficeObj = RecruiterOffice::where(['id' => $allData->selectedOffice[0]->selectedOfficeId])->first();
            dd($allData);
            if ($jobDetails['job_type'] == $allData->selectedJobType) {
                $this->sameOfficeOrNot($jobDetails, $allData, $recruiterOfficeObj);
            } else {
                $this->sameOfficeOrNot($jobDetails, $allData, $recruiterOfficeObj);
                RecruiterJobs::where(['id', $allData->jobId])->delete();
            }
//            DB::commit();
        } catch (\Exception $e) {
//            DB::rollback();
            $this->result['success'] = false;
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
    private function sameOfficeOrNot($jobDetails, $allData, $recruiterOfficeObj){
        try{
            if ((string) $recruiterOfficeObj['latitude'] == (string) $allData->selectedOffice[0]->selectedOfficeLat && (string) $recruiterOfficeObj['longitude'] == (string) $allData->selectedOffice[0]->selectedOfficeLng) {
                $this->updateJob($jobDetails['job_type'], $allData);
                $this->updateOffice($allData);
                $this->updateOfficeType($allData);
            } else {
                $newOfficeObj = $this->updateOffice($allData, 1);
                $this->updateJob($allData->selectedJobType, $allData, $newOfficeObj['id']);
                $this->updateOfficeType($allData, 1);
            }
            $this->result = true;
        } catch (\Exception $e) {
            $this->result = $e->getMessage();
        }
        return $this->result;
    }

    private function updateOffice($allData, $requestType = '') {
        try {
            if($requestType != ''){
                $recruiterOfficeObj = new RecruiterOffice();
                $recruiterOfficeObj->address = $allData->selectedOffice[0]->selectedOfficeAddress;
                $recruiterOfficeObj->latitude = $allData->selectedOffice[0]->selectedOfficeLat;
                $recruiterOfficeObj->longitude = $allData->selectedOffice[0]->selectedOfficeLng;
                $recruiterOfficeObj->zipcode = $allData->selectedOffice[0]->selectedOfficeZipcode;
            }else{
                $recruiterOfficeObj = RecruiterOffice::where(['id' => $allData->selectedOffice[0]->selectedOfficeId])->first();
            }

            $recruiterOfficeObj->work_everyday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isEverydayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->everydayStart)) : null;
            $recruiterOfficeObj->work_everyday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isEverydayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->everydayEnd)) : null;
            $recruiterOfficeObj->monday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isMondayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->mondayStart)) : null;
            $recruiterOfficeObj->monday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isMondayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->mondayEnd)) : null;
            $recruiterOfficeObj->tuesday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isTuesdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->tuesdayStart)) : null;
            $recruiterOfficeObj->tuesday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isTuesdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->tuesdayEnd)) : null;
            $recruiterOfficeObj->wednesday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isWednesdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->wednesdayStart)) : null;
            $recruiterOfficeObj->wednesday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isWednesdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->wednesdayEnd)) : null;
            $recruiterOfficeObj->thursday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isThursdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->thursdayStart)) : null;
            $recruiterOfficeObj->thursday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isThursdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->thursdayEnd)) : null;
            $recruiterOfficeObj->friday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isFridayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->fridayStart)) : null;
            $recruiterOfficeObj->friday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isFridayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->fridayEnd)) : null;
            $recruiterOfficeObj->saturday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isSaturdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->saturdayStart)) : null;
            $recruiterOfficeObj->saturday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isSaturdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->saturdayEnd)) : null;
            $recruiterOfficeObj->sunday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isSundayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->sundayStart)) : null;
            $recruiterOfficeObj->sunday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isSundayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->sundayEnd)) : null;
            $recruiterOfficeObj->phone_no = $allData->selectedOffice[0]->selectedOfficePhone;
            $recruiterOfficeObj->office_info = $allData->selectedOffice[0]->selectedOfficeInfo;
            $recruiterOfficeObj->user_id = Auth::user()->id;
            $recruiterOfficeObj->save();
            $result = $recruiterOfficeObj;
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        return $result;
    }

    private function updateJob($jobType, $allData, $officeId = '') {
        try {
            $jobObj = RecruiterJobs::where(['id' => $allData->jobId])->first();
            $jobTemplateId = $jobObj['job_templated_id'];
            if($officeId != ''){
                RecruiterJobs::where(['id' => $allData->jobId])->delete();
                $jobObj = new RecruiterJobs();
                $jobObj->job_templated_id = $jobTemplateId;
                $jobObj->recruiter_office_id = $allData->selectedOffice[0]->selectedOfficeId;
            }
            $jobObj->job_type = $jobType;
            if ($jobType == 2) {
                $jobObj->is_monday = in_array("Monday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_tuesday = in_array("Tuesday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_wedensday = in_array("Wednesday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_thursday = in_array("Thursday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_friday = in_array("Friday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_saturday = in_array("Saturday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_sunday = in_array("Sunday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->save();
            } elseif ($jobType == 3) {
                $jobObj->no_of_jobs = $allData->totalJobOpening;
                $jobObj->save();
                TempJobDates::where(['recruiter_job_id' => $allData->jobId])->delete();
                $newTemJobObj = new TempJobDates();
                foreach ($allData->tempJobDates as $tempJobDate) {
                    $newTemJobObj->recruiter_job_id = $allData->jobId;
                    $newTemJobObj->job_date = $tempJobDate;
                    $newTemJobObj->save();
                }
            }
            $this->result = $jobObj;
        } catch (\Exception $e) {
            $this->result = $e->getMessage();
        }
        return $this->result;
    }
    
    public function updateOfficeType($allData, $requestType = ''){
        try{
            if($requestType == ''){
                RecruiterOfficeType::where(['recruiter_office_id' => $allData->selectedOffice[0]->selectedOfficeId])->delete();
            }
            $newRecruiterOfficeTypeObj = new RecruiterOfficeType();
            foreach($allData->allOfficeTypeDetail as $officeType){
                if(in_array($officeType['officetype_name'], $allData->selectedOffice[0]->selectedOfficeType)){
                    $newRecruiterOfficeTypeObj->recruiter_office_id = $allData->selectedOffice[0]->selectedOfficeId;
                    $newRecruiterOfficeTypeObj->office_type_id = $officeType['id'];
                    $newRecruiterOfficeTypeObj->save();
                }
            }
            $this->result = true;
        } catch (\Exception $e) {
            $this->result = $e->getMessage();
        }
        return $this->result;
    }

}
