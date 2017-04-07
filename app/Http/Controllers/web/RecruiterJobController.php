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
use App\Models\RecruiterOffice;
use App\Models\ChatUserLists;
use App\Models\JobSeekerProfiles;
use App\Providers\NotificationServiceProvider;
use App\Models\Device;
use App\Models\Notification;
use App\Models\OfficeType;
use App\Models\Configs;
use App\Models\RecruiterOfficeType;
use DB;
use Log;
use App\Http\Requests\CheckJobAppliedOrNotRequest;
use Session;
use App\Models\JobRatings;
use App\Models\SavedJobs;

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
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function searchSeekers(Request $request,$jobId){
        try{
            $userId = Auth::user()->id;
            $searchData = $request->all();
            $maxDistance = Configs::getSearchRadius();
            $distance = $request->get('distance');
            $availAll = $request->get('avail_all');
            if(empty($distance))
                $distance = $maxDistance;
            if(empty($availAll)) {
                $availAll = 0;
            }
            $searchData['distance']     = $distance;
            $searchData['avail_all']    = $availAll;
            $jobDetails     = RecruiterJobs::getRecruiterJobDetails($jobId);
            $seekersList    = JobSeekerProfiles::getJobSeekerProfiles($jobDetails,$searchData);
            $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);

            if ($request->ajax()) {
                return view('web.recuriterJob.seekersData', ['seekersList' => $seekersList, 'jobDetails' => $jobDetails, 'searchData' => $searchData, 'jobId'=>$jobId,'maxDistance'=>$maxDistance, 'jobTemplateModalData'=>$jobTemplateModalData])->render();  
            }

            return view('web.recuriterJob.search', compact('seekersList','jobDetails','searchData', 'jobId','maxDistance', 'jobTemplateModalData'));
        } catch (\Exception $e) {
            Log::error($e);
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
            
            $tempPrevious = RecruiterJobs::chkTempJObRatingPending();
            if($tempPrevious) {
                $tempPreviousArray = $tempPrevious->toArray();
                foreach($tempPreviousArray as $previousTempJob){
                    $tempJobLastDate = date("Y-m-d", strtotime($previousTempJob['job_date']." +1 days"));
                    if(($previousTempJob['total_hired'] != $previousTempJob['total_rating']) && ($tempJobLastDate <= date("Y-m-d"))){
                        Session::flash('message', trans('messages.rate_previous_jobseeker'));
                        return redirect('createJob/'.$request->templateId);
                    }
                }
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
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function listJobs(Request $request) {
        try{
            $userId = Auth::user()->id;
            $this->viewData['jobList'] = RecruiterJobs::getJobs();
            $this->viewData['jobTemplateModalData'] = JobTemplates::getAllUserTemplates($userId);
            
            if ($request->ajax()) {
                return view('web.recuriterJob.jobData', ['jobList' => $this->viewData['jobList'], 'jobTemplateModalData' => $this->viewData['jobTemplateModalData']])->render();
            }

            return $this->returnView('list');
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function editJob(Request $request, $jobId) {
        try {
            dd('In progress');
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function jobDetails(Request $request,$jobId) {
        try{
            $userId = Auth::user()->id;
            $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);
            $this->viewData['skills'] = TemplateSkills::getTemplateSkills($this->viewData['job']['job_template_id']);
            $this->viewData['seekerList'] = JobLists::getJobSeekerWithRatingList($this->viewData['job']);
            $this->viewData['jobTemplateModalData'] = JobTemplates::getAllUserTemplates($userId);
            return $this->returnView('view');
        } catch (\Exception $e) {
            Log::error($e);
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
                }else if ($requestData['appliedStatus'] == JobLists::REJECTED){
                    $this->sendPushUser($requestData['appliedStatus'], Auth::user()->id, $jobData->seeker_id, $requestData['jobId']);
                }
            }else{
                $inviteJobs = array('seeker_id' => $requestData['seekerId'] , 'recruiter_job_id' => $requestData['jobId'] , 'applied_status' => JobLists::INVITED);
                JobLists::insert($inviteJobs);
                $this->sendPushUser($requestData['appliedStatus'], Auth::user()->id, $requestData['seekerId'], $requestData['jobId']);
            }
            return redirect('job/details/'.$requestData['jobId']);
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

    public function jobSeekerDetails($seekerId, $jobId) {
        try {
            $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);
            $seekerDetails = JobSeekerProfiles::getJobSeekerDetails($seekerId,$this->viewData['job']);
            return view('web.recuriterJob.seekerdetails',compact('seekerDetails', 'jobId'));

        } catch (\Exception $e) {
            Log::error($e);
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
        }else if ($jobstatus == JobLists::REJECTED) {
            $notificationData = array(
                'notificationData' => $jobDetails['office_name'] . " has rejected your job application for " . $jobDetails['jobtitle_name'],
                'notification_title' => 'User shortlisted',
                'sender_id' => $sender,
                'type' => 1,
                'notificationType' => Notification::REJECTED,
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
        $data = ['receiver_id'=>$receiverId,'job_list_id' => $jobId,'sender_id' => $sender, 'notification_data'=>$notificationData['notificationData'],'notification_type' => $notificationData['notificationType']];
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
        $userId = Auth::user()->id;
        $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
        return view('web.recuriterJob.edit', compact('jobId', 'jobTemplateModalData'));
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
            Log::error($e);
            $response = $e->getMessage();
        }
        return $response;
    }

    public function postEditJob(Request $request) {
        try {
            DB::beginTransaction();
            $allData = json_decode($request->jobDetails);

            //Log::info(print_r($allData, true));

            if ($allData->selectedJobType == config('constants.FullTime')) {
                $allData->selectedJobType = config('constants.OneValue');
            } else if ($allData->selectedJobType == config('constants.PartTime')) {
                $allData->selectedJobType = config('constants.PartTimeJob');
            } else {
                $allData->selectedJobType = config('constants.TemporaryJob');
            }
            $jobDetails = RecruiterJobs::getRecruiterJobDetails($allData->jobId);
            //Log::info("Job Detail");
            //Log::info(print_r($jobDetails, true));
            
            $recruiterOfficeObj = RecruiterOffice::where(['id' => $jobDetails['recruiter_office_id']])->first();
            //Log::info("recruiterOfficeObj");
            //Log::info(print_r($recruiterOfficeObj, true));
            
            $updatedJob = $this->sameOfficeOrNot($allData, $recruiterOfficeObj);
            
            DB::commit();
            //Log::info("finaldata");
            //Log::info(print_r($updatedJob, true));
            
            $this->result['data'] = $updatedJob['data'];
            $this->result['success'] = true;
            $this->result['message'] = trans('messages.job_edited');
        } catch (\Exception $e) {
            DB::rollback();
            //Log::error($e);
            $this->result['success'] = false;
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }

    private function sameOfficeOrNot($allData, $recruiterOfficeObj) {
        try {
            if ((string) $recruiterOfficeObj['latitude'] == (string) $allData->selectedOffice[0]->selectedOfficeLat && (string) $recruiterOfficeObj['longitude'] == (string) $allData->selectedOffice[0]->selectedOfficeLng) {
                $updatedJob = $this->updateJob($allData->selectedJobType, $allData);
                //Log::info("sameOffice:updatedJobIf");
                //Log::info(print_r($updatedJob, true));

                $this->updateOffice($allData);
                $this->updateOfficeType($allData);
            } else {
                $updatedJob = $this->checkingOffice($allData, $recruiterOfficeObj);
                //Log::info("sameOffice:checkingOffice:updatedJobElse");
                //Log::info(print_r($updatedJob, true));
            }
            
            $this->result['data'] = $updatedJob['data'];
            $this->result['success'] = true;
            //Log::info("sameOffice:result");
            //Log::info(print_r($this->result, true));
            
        } catch (\Exception $e) {
            //Log::error($e);
            $this->result['success'] = $e->getMessage();
        }
        return $this->result;
    }

    private function checkingOffice($allData, $recruiterOfficeObj) {
        try {
            $recruiterOfficeObj = RecruiterOffice::where([
                        'latitude' => $allData->selectedOffice[0]->selectedOfficeLat,
                        'longitude' => $allData->selectedOffice[0]->selectedOfficeLng,
                        'user_id' => Auth::user()->id
                    ])->first();
            
            //Log::info("checkingOffice:recruiterOfficeObj");
            //Log::info(print_r($recruiterOfficeObj, true));
            
            if($recruiterOfficeObj != null){
                $this->saveOffice($recruiterOfficeObj->id, $allData);
                //Log::info("checkingOffice:saveOfficeIfNull");
                
                $updatedJob = $this->updateJob($allData->selectedJobType, $allData, $recruiterOfficeObj);
                //Log::info("checkingOffice:updateJob");
                //Log::info(print_r($updatedJob, true));
                
                $this->updateOfficeType($allData, $recruiterOfficeObj->id);
                //Log::info("checkingOffice:officeType");
                
                DB::table('recruiter_jobs')->where('id', $allData->jobId)->delete();
            }else{
                $newOfficeObj = $this->updateOffice($allData, config('constants.OneValue'));
                //Log::info("checkingOffice:newOfficeElse");
                //Log::info(print_r($newOfficeObj, true));
                
                $updatedJob =  $this->updateJob($allData->selectedJobType, $allData, $newOfficeObj);
                //Log::info("checkingOffice:updateOfficeElse");
                //Log::info(print_r($updatedJob, true));
                
                $this->updateOfficeType($allData, $newOfficeObj['id']);
                //Log::info("checkingOffice:officeType");
                
                DB::table('recruiter_jobs')->where('id', $allData->jobId)->delete();
                RecruiterOffice::where('id', $allData->selectedOffice[0]->selectedOfficeId)->delete();
            }
            $this->result['data'] = $updatedJob;
            $this->result['success'] = true;
        } catch (\Exception $e) {
            //Log::error($e);
            $this->result['success'] = $e->getMessage();
        }
        return $this->result;
    }
    
    private function saveOffice($recruiterOfficeId, $allData){
        try{
            $recruiterOfficeObj = RecruiterOffice::where(['id' => $recruiterOfficeId])->first();
            $recruiterOfficeObj->work_everyday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isEverydayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->everydayStart)) : null;
            $recruiterOfficeObj->work_everyday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isEverydayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->everydayEnd)) : null;
            $recruiterOfficeObj->monday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isMondayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->mondayStart)) : null;
            $recruiterOfficeObj->monday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isMondayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->mondayEnd)) : null;
            $recruiterOfficeObj->tuesday_start = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isTuesdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->tuesdayStart)) : null;
            $recruiterOfficeObj->tuesday_end = ($allData->selectedOffice[0]->selectedOfficeWorkingHours->isTuesdayWork == true) ? date('H:i:s', strtotime($allData->selectedOffice[0]->selectedOfficeWorkingHours->tuesdayEnd)) : '';
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
            $this->result['success'] = true;
            
            //Log::info("saveOfficeMethod:recruiterOfficeObj");
            //Log::info(print_r($recruiterOfficeObj, true));
        } catch (\Exception $e) {
            //Log::error($e);
            $this->result['success'] = $e->getMessage();
        }
        
        //Log::info("saveOfficeMethod:result");
        //Log::info(print_r($this->result, true));
        
        return $this->result;
    }

    private function updateOffice($allData, $requestType = '') {
        try {
            if ($requestType != '') {
                $recruiterOfficeObj = new RecruiterOffice();
                $recruiterOfficeObj->address = $allData->selectedOffice[0]->selectedOfficeAddress;
                $recruiterOfficeObj->latitude = $allData->selectedOffice[0]->selectedOfficeLat;
                $recruiterOfficeObj->user_id = Auth::user()->id;
                $recruiterOfficeObj->longitude = $allData->selectedOffice[0]->selectedOfficeLng;
                $recruiterOfficeObj->zipcode = $allData->selectedOffice[0]->selectedOfficeZipcode;
                $recruiterOfficeObj->phone_no = $allData->selectedOffice[0]->selectedOfficePhone;
                $recruiterOfficeObj->office_info = $allData->selectedOffice[0]->selectedOfficeInfo;
                $recruiterOfficeObj->save();
            } else {
                $recruiterOfficeObj = RecruiterOffice::where(['id' => $allData->selectedOffice[0]->selectedOfficeId])->first();
            }
            //Log::info("updateOfficeMethod:recruiterOfficeObj");
            //Log::info(print_r($recruiterOfficeObj, true));
            
            $this->saveOffice($recruiterOfficeObj->id, $allData);
            //Log::info("updateOfficeMethod:saveOffice");
            
            $updateOfficeResult = $recruiterOfficeObj;
        } catch (\Exception $e) {
            //Log::error($e);
            $updateOfficeResult = $e->getMessage();
        }
        return $updateOfficeResult;
    }

    private function updateJob($jobType, $allData, $office = '') {
        try {
            $jobObj = RecruiterJobs::where(['id' => $allData->jobId])->first();
            //Log::info("updateJobMethod:jobObj");
            //Log::info(print_r($jobObj, true));
            
            $jobTemplateId = $jobObj['job_template_id'];
            if ($office != '') {
                $jobObj = new RecruiterJobs();
                $jobObj->job_template_id = $jobTemplateId;
                $jobObj->recruiter_office_id = $office['id'];
            }
            $jobObj->job_type = $jobType;

            $jobObj->is_monday = config('constants.NullValue');
            $jobObj->is_tuesday = config('constants.NullValue');
            $jobObj->is_wednesday = config('constants.NullValue');
            $jobObj->is_thursday = config('constants.NullValue');
            $jobObj->is_friday = config('constants.NullValue');
            $jobObj->is_saturday = config('constants.NullValue');
            $jobObj->is_sunday = config('constants.NullValue');
            $jobObj->no_of_jobs = config('constants.NullValue');
            TempJobDates::where(['recruiter_job_id' => $allData->jobId])->forceDelete();
            if ($jobType == config('constants.PartTimeJob')) {
                $jobObj->is_monday = in_array("Monday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_tuesday = in_array("Tuesday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_wednesday = in_array("Wednesday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_thursday = in_array("Thursday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_friday = in_array("Friday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_saturday = in_array("Saturday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->is_sunday = in_array("Sunday", $allData->partTimeDays) ? 1 : 0;
                $jobObj->no_of_jobs = config('constants.NullValue');
                $jobObj->save();
                //Log::info("updateJobMethod:PartTime");
                //Log::info(print_r($jobObj, true));
                
            } elseif ($jobType == config('constants.TemporaryJob')) {
                $jobObj->no_of_jobs = $allData->totalJobOpening;
                $jobObj->save();
                foreach ($allData->tempJobDates as $tempJobDate) {
                    $newTemJobObj = new TempJobDates();
                    $newTemJobObj->recruiter_job_id = $jobObj['id'];
                    $newTemJobObj->job_date = date('Y-m-d', strtotime($tempJobDate));
                    $newTemJobObj->save();
                    
                    //Log::info("updateJobMethod:newTemJobObj");
                    //Log::info(print_r($newTemJobObj, true));
                }
            } else {
                $jobObj->save();
                //Log::info("updateJobMethod:FullTime");
                //Log::info(print_r($jobObj, true));
            }
            
        } catch (\Exception $e) {
            //Log::error($e);
            $jobObj = $e->getMessage();
        }
        return $jobObj;
    }

    public function updateOfficeType($allData, $officeId = '') {
        try {
            if ($officeId == '') {
                RecruiterOfficeType::where(['recruiter_office_id' => $allData->selectedOffice[0]->selectedOfficeId])->delete();
            }else{
                RecruiterOfficeType::where(['recruiter_office_id' => $officeId])->delete();
            }
            $allOfficeTypes = OfficeType::allOfficeTypes();
            foreach ($allOfficeTypes as $officeType) {
                if (in_array($officeType['officetype_name'], $allData->selectedOffice[0]->selectedOfficeType)) {
                    $newRecruiterOfficeTypeObj = new RecruiterOfficeType();
                    if ($officeId != '') {
                        $newRecruiterOfficeTypeObj->recruiter_office_id = $officeId;
                    } else {
                        $newRecruiterOfficeTypeObj->recruiter_office_id = $allData->selectedOffice[0]->selectedOfficeId;
                    }
                    $newRecruiterOfficeTypeObj->office_type_id = $officeType['id'];
                    $newRecruiterOfficeTypeObj->save();
                }
            }
            $updateOfficeTypeResult = true;
        } catch (\Exception $e) {
            //Log::error($e);
            $updateOfficeTypeResult = $e->getMessage();
        }
        
        //Log::info("updateOfficeTypeMethod");
        //Log::info(print_r($updateOfficeTypeResult, true));
        
        return $updateOfficeTypeResult;
    }
    
    public function postDeleteJob(Request $request){
        try{
            $insertData = [];
            $jobId = $request->jobId;
            $jobObj = RecruiterJobs::where('id', $jobId)->first();
            if($jobObj) {
                $jobData = RecruiterJobs::where('recruiter_jobs.id',$jobId)
                                ->select('job_lists.seeker_id', 'job_templates.user_id', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name')
                                ->join('job_templates', 'job_templates.id','=','recruiter_jobs.job_template_id')
                                ->join('job_lists', 'job_lists.recruiter_job_id', 'recruiter_jobs.id')
                                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                                ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'job_templates.user_id')
                                ->groupby('recruiter_jobs.id')
                                ->groupby('job_lists.seeker_id')
                                ->get();
                $list = $jobData->toArray();
                if(!empty($list)) {
                    $pushList = array_map(function ($value) {
                                        return  $value;
                                }, $list);
                }
                if(!empty($pushList)) {
                    foreach($pushList as $value) {
                        $message = "Delete job notification | ".$value['office_name']." has deleted the temporary job vacancy for ".$value['jobtitle_name'];
                        $userId = $value['seeker_id'];
                        $senderId = $value['user_id'];

                        $deviceModel = Device::getDeviceToken($userId);
                        if($deviceModel) {
                            $insertData[] = ['receiver_id'=>$userId,
                            'sender_id'=>$senderId,
                            'notification_data'=>$message,
                            'created_at'=>date('Y-m-d h:i:s'),
                            'notification_type' => Notification::OTHER,
                            ];
                            
                           NotificationServiceProvider::sendPushNotification($deviceModel, $message);
                        }
                    }
                    if(!empty($insertData)) {
                        Notification::createNotification($insertData);
                    }
                }
                
                JobLists::where('recruiter_job_id', $jobId)->delete();
                JobRatings::where('recruiter_job_id', $jobId)->delete();
                TempJobDates::where('recruiter_job_id', $jobId)->delete();
                RecruiterJobs::where('id', $jobId)->delete();
                SavedJobs::where('recruiter_job_id', $jobId)->delete();
                Notification::where('job_list_id', $jobId)->delete();
            }
            if(!empty($request->requestOrigin)) {
                Session::flash('message', trans('messages.job_deleted'));
                return redirect('job/lists');
            }
            $this->result['success'] = true;
            $this->result['message'] = trans('messages.job_deleted');
        } catch (\Exception $e) {
            Log::error($e);
            $this->result['success'] = false;
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
    public function appliedOrNot(CheckJobAppliedOrNotRequest $request){
        try{
            $jobs = RecruiterOffice::join('recruiter_jobs', 'recruiter_jobs.recruiter_office_id' ,'=', 'recruiter_offices.id')
                    ->join('job_lists', 'job_lists.recruiter_job_id' ,'=', 'recruiter_jobs.id')
                    ->where('recruiter_offices.id', $request->officeId)
                    ->select('job_lists.recruiter_job_id as recruiter_job_id')
                    ->get();
            $this->result['data'] = $jobs;
        } catch (\Exception $e) {
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
    public function jobSeekerProfile($seekerId) {
        try {
            $seekerDetails = JobSeekerProfiles::getJobSeekerProfile($seekerId);
            return view('web.recuriterJob.seekerProfile',compact('seekerDetails'));

        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

}
