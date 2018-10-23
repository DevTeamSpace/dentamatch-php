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
use App\Models\RecruiterOfficeType;
use DB;
use Log;
use App\Http\Requests\CheckJobAppliedOrNotRequest;
use Session;
use App\Models\JobRatings;
use App\Models\SavedJobs;
use App\Models\JobseekerTempHired;
use App\Models\PreferredJobLocation;
use App\Models\User;
use App\Helpers\NotificationHelper;
use Mail;
use App\Models\Configs;
use Illuminate\Support\Facades\Storage;

class RecruiterJobController extends Controller {

    protected $user, $viewData, $result;

    public function __construct() {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->viewData = ['navActive' => 'joblisting',];
    }

      /**
     * Method to view user data on dashboard
     * @return view
     */
    public function dashboard(){
        try {
            $activeTab = 3;
            $userId = Auth::user()->id;
            $currentDay = date("l"); 
            $currentDate = date('F d, Y',time());
            $userDetails = User::getUser($userId);
            $hiredListByCurrentDate = JobseekerTempHired::getCurrentDayJobSeekerList();
            $latestMessage = ChatUserLists::getSeekerListForChatDashboard(Auth::id());
            $latestNotifications = NotificationHelper::topNotificationList($userId);
            $notificationAdminModel = NotificationHelper::notificationAdmin($userId);
            $notificationAdmin = [];
            if($notificationAdminModel) {
                $notificationAdmin = json_decode($notificationAdminModel->notification_data); 
            }
            
            $jobList = RecruiterJobs::getJobs(3);
            $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);

            $ts = strtotime(date("Y-m-d"));
            $start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
            
            $wkEndDay = strtotime('next sunday', $start);
            
            $currentWeekCalendar = RecruiterJobs::getDashboardCalendarData($start);
            while($wkEndDay>=$start){
                $nextDay = date('Y-m-d', $start);
                if(!isset($currentWeekCalendar[$nextDay])){
                    $currentWeekCalendar[$nextDay]=[];
                }
                $start = strtotime('+1 day', $start);
            }
            ksort($currentWeekCalendar);
                        
            return view('web.user-dashboard', compact('activeTab','currentDay','currentDate','userDetails','hiredListByCurrentDate','latestMessage', 'latestNotifications', 'notificationAdminModel','notificationAdmin', 'jobList', 'currentWeekCalendar', 'jobTemplateModalData'));
        
        }  catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }
    
    public function returnView($viewFileName) {
        return view('web.recuriterJob.' . $viewFileName, $this->viewData);
    }

      /**
     * Method to create a job
     * @return view
     */
    public function createJob($templateId) {
        try {
            $this->viewData['offices'] = RecruiterOffice::getAllRecruiterOffices(Auth::user()->id);
            $this->viewData['templateId'] = $templateId;
            $this->viewData['jobTemplates'] = JobTemplates::findById($templateId);
            $this->viewData['preferredLocationId'] = PreferredJobLocation::getAllPreferrefJobLocation();
            $payrate = Configs::select('config_data')->where('config_name','=','PAYRATE')->first();
            $this->viewData['payrateUrl']='';
            if($payrate->config_data!=null){
                $this->viewData['payrateUrl'] = Storage::url($payrate->config_data);
            }

            return $this->returnView('create');
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

      /**
     * Method to search job seekers 
     * @return view
     */
    public function searchSeekers(Request $request,$jobId){
        try{
            $userId = Auth::user()->id;
            $searchData = $request->all();
            $preferredLocationId = $request->get('preferredLocationId');
            $availAll = $request->get('avail_all');
            if(empty($availAll)) {
                $availAll = 0;
            }
            if(empty($preferredLocationId)) {
                $preferredLocationId = "";
            }
            $searchData['avail_all']    = $availAll;
            $searchData['preferredLocationId']    = $preferredLocationId;
            $jobDetails     = RecruiterJobs::getRecruiterJobDetails($jobId);
            $seekersList    = JobSeekerProfiles::getJobSeekerProfiles($jobDetails,$searchData);
            $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
            $preferredLocations = PreferredJobLocation::getAllPreferrefJobLocation();
            if ($request->ajax()) {
                return view('web.recuriterJob.seekers-data', ['seekersList' => $seekersList, 'jobDetails' => $jobDetails, 'searchData' => $searchData, 'jobId'=>$jobId,'jobTemplateModalData'=>$jobTemplateModalData, 'preferredLocations'=>$preferredLocations, 'preferredLocationId'=>$preferredLocationId])->render();  
            }
            return view('web.recuriterJob.search', compact('seekersList','jobDetails','searchData', 'jobId', 'jobTemplateModalData', 'preferredLocations','preferredLocationId'));
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

      /**
     * Method to save/update a job
     * @return view
     */
    public function saveOrUpdate(Request $request) {
        $this->validate($request, [
            'templateId' => 'required',
            'dentalOfficeId' => 'required|integer',
            'jobType' => 'required|in:1,2,3',
            'partTimeDays' => 'required_if:jobType,2',
            'tempDates' => 'required_if:jobType,3',
            'noOfJobs' => 'required_if:jobType,3',
            'action' => 'required|in:add,edit',
            'id' => 'integer|required_if:action,edit',
            'preferredJobLocationId' => 'integer|required'
        ]);
        try {

            DB::beginTransaction();
            $recruiterJobObj = new RecruiterJobs();
            if ($request->action == "edit" && !empty($request->id)) {
                $recruiterJobObj = RecruiterJobs::findById($request->id);
            }
            if ($request->jobType == RecruiterJobs::TEMPORARY) {
                $tempPrevious = RecruiterJobs::chkTempJObRatingPending();
                if($tempPrevious) {
                    $tempPreviousArray = $tempPrevious->toArray();
                    foreach($tempPreviousArray as $previousTempJob){
                        $tempJobLastDate = date("Y-m-d", strtotime($previousTempJob['job_date']." +1 days"));
                        if(($previousTempJob['total_hired'] != $previousTempJob['total_rating']) && ($tempJobLastDate >= date("Y-m-d"))){
                            Session::flash('message', trans('messages.rate_previous_jobseeker'));
                            return redirect('createJob/'.$request->templateId);
                        }
                    }
                }
            }
            $recruiterJobObj->job_template_id = $request->templateId;
            $recruiterJobObj->recruiter_office_id = $request->dentalOfficeId;
            $recruiterJobObj->job_type = $request->jobType;
            $recruiterJobObj->pay_rate = $request->payRate;
            $recruiterJobObj->no_of_jobs = ($request->noOfJobs != '') ? $request->noOfJobs : 0;
            $recruiterJobObj->preferred_job_location_id = $request->preferredJobLocationId;

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

      /**
     * Method to get list of jobs
     * @return view
     */
    public function listJobs(Request $request) {
        try{
            $userId = Auth::user()->id;
            $this->viewData['jobList'] = RecruiterJobs::getJobs();
            $this->viewData['jobTemplateModalData'] = JobTemplates::getAllUserTemplates($userId);
            
            if ($request->ajax()) {
                return view('web.recuriterJob.job-data', ['jobList' => $this->viewData['jobList'], 'jobTemplateModalData' => $this->viewData['jobTemplateModalData']])->render();
            }

            return $this->returnView('list');
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

      /**
     * Method to view job details
     * @return view
     */
    public function jobDetails(Request $request,$jobId) {
        try{
            $userId = Auth::user()->id;
            if(RecruiterJobs::where('id', $jobId)->first()){
                $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);
                $this->viewData['skills'] = TemplateSkills::getTemplateSkills($this->viewData['job']['job_template_id']);
                $this->viewData['seekerListHired'] = $this->getData($this->viewData['job'],JobLists::HIRED);
                $this->viewData['seekerListInvited'] = $this->getData($this->viewData['job'],JobLists::INVITED);
                $this->viewData['seekerListSortListed'] = $this->getData($this->viewData['job'],JobLists::SHORTLISTED);
                $this->viewData['seekerListApplied'] = $this->getData($this->viewData['job'],JobLists::APPLIED);
                $this->viewData['jobTemplateModalData'] = JobTemplates::getAllUserTemplates($userId);
                return $this->returnView('view');
            }else{
                return redirect('job/lists');    
            }
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }
    
      /**
     * Method to get seeker details against a job
     * @return view
     */
    public function getJobSeekerDetails(Request $request,$jobId,$appliedStatus){
        $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);
        $this->viewData['seekerList'] = $this->getData($this->viewData['job'],$appliedStatus);
        if($appliedStatus == 1){
            $this->viewData['status'] = 'Invited';
        }else if($appliedStatus == 2){
            $this->viewData['status'] = 'Applied';
        }else if($appliedStatus == 3){
            $this->viewData['status'] = 'Shortlisted';
        }else if($appliedStatus == 4){
            $this->viewData['status'] = 'Hired';
        }
        
        $this->viewData['totalCount'] = $this->viewData['seekerList']->total();
        return $this->returnView('job-seeker-details');
    }
    
      /**
     * Method to get rating of jobs
     * @return view
     */
    public function getData($job,$dataType){
        return JobLists::getJobSeekerWithRatingList($job,$dataType);
    }

      /**
     * Method to update job status
     * @return view
     */
    public function updateStatus(Request $request) {
        $this->validate($request, [
            'jobId' => 'required|integer',
            'seekerId' => 'required|integer',
            'appliedStatus' => 'required|integer',
            'jobType' => 'required|integer',
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
                return redirect('job/details/'.$requestData['jobId']);
            }else{
                $inviteJobs = array('seeker_id' => $requestData['seekerId'] , 'recruiter_job_id' => $requestData['jobId'] , 'applied_status' => JobLists::INVITED);
                JobLists::insert($inviteJobs);
                $this->sendPushUser($requestData['appliedStatus'], Auth::user()->id, $requestData['seekerId'], $requestData['jobId']);
                Session::flash('message', trans('messages.invited_success'));
                if($requestData['jobType']==3){
                    Session::flash('message', trans('messages.invited_success_temp'));
                }
                return redirect('job/search/'.$requestData['jobId']);
            }
        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

      /**
     * Method to get seeker details
     * @return view
     */
    public function jobSeekerDetails($seekerId, $jobId) {
        try {
            $matchedSkills = RecruiterJobs::getMatchingSkills($jobId, $seekerId);
            $jobDetails = RecruiterJobs::getRecruiterJobDetails($jobId);
            $seekerDetails = JobSeekerProfiles::getJobSeekerDetails($seekerId,$jobDetails);
            return view('web.recuriterJob.seekerdetails',compact('seekerDetails', 'jobId', 'jobDetails', 'matchedSkills'));

        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

      /**
     * Method to send push
     * @return view
     */
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
                'notification_title' => 'User rejected',
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
            NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['notificationData'], $params,$receiverId);
        }elseif(!$deviceModel && $jobstatus == JobLists::INVITED){
            $email = \App\Models\User::where('id',$receiverId)->first();
            $name = \App\Models\JobSeekerProfile::where('user_id',$receiverId)->first();
            $dataName = $name['first_name'];
            Mail::queue('email.new-invite', ['name' => $dataName ], function ($message) use ($email) {
            $message->to($email['email'])
                 ->subject(trans("messages.new_job_invite"));
            });
        }
    }

      /**
     * Method to edit job
     * @return view
     */
    public function jobEdit(Request $request, $jobId) {
        $userId = Auth::user()->id;
        $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
        $payrate = Configs::select('config_data')->where('config_name','=','PAYRATE')->first();
        $payrateUrl='';
        if($payrate->config_data!=null){
            $payrateUrl = Storage::url($payrate->config_data);
        }
        return view('web.recuriterJob.edit', compact('jobId', 'jobTemplateModalData','payrateUrl'));
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
            $allData['preferredJobLocations'] = PreferredJobLocation::getAllPreferrefJobLocation();
            $response = $allData;
        } catch (\Exception $e) {
            Log::error($e);
            $response = $e->getMessage();
        }
        return $response;
    }

     /**
     * Method to edit job
     * @return view
     */
    public function postEditJob(Request $request) {
        try {
            DB::beginTransaction();
            $allData = json_decode($request->jobDetails);
             if ($allData->selectedJobType == config('constants.FullTime')) {
                $allData->selectedJobType = config('constants.OneValue');
            } else if ($allData->selectedJobType == config('constants.PartTime')) {
                $allData->selectedJobType = config('constants.PartTimeJob');
            } else {
                $allData->selectedJobType = config('constants.TemporaryJob');
            }
            $jobDetails = RecruiterJobs::getRecruiterJobDetails($allData->jobId);
                      
            $recruiterOfficeObj = RecruiterOffice::where(['id' => $jobDetails['recruiter_office_id']])->first();
            
            $updatedJob = $this->sameOfficeOrNot($allData, $recruiterOfficeObj);
            
            DB::commit();
           
            $this->result['data'] = $updatedJob['data'];
            $this->result['success'] = true;
            $this->result['message'] = trans('messages.job_edited');
        } catch (\Exception $e) {
            DB::rollback();
            $this->result['success'] = false;
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }

     /**
     * Method to check same office in edit job or not
     * @return json
     */
    private function sameOfficeOrNot($allData, $recruiterOfficeObj) {
        try {
            if ((string) $recruiterOfficeObj['latitude'] == (string) $allData->selectedOffice[0]->selectedOfficeLat && (string) $recruiterOfficeObj['longitude'] == (string) $allData->selectedOffice[0]->selectedOfficeLng) {
                $updatedJob = $this->updateJob($allData->selectedJobType, $allData);
           
                $this->updateOffice($allData);
                $this->updateOfficeType($allData);
            } else {
                $updatedJob = $this->checkingOffice($allData, $recruiterOfficeObj);             
            }
            
            $this->result['data'] = $updatedJob['data'];
            $this->result['success'] = true;
            
        } catch (\Exception $e) {
            $this->result['success'] = $e->getMessage();
        }
        return $this->result;
    }

     /**
     * Method to check offce 
     * @return view
     */
    private function checkingOffice($allData, $recruiterOfficeObj) {
        try {
            $recruiterOfficeObj = RecruiterOffice::where([
                        'latitude' => $allData->selectedOffice[0]->selectedOfficeLat,
                        'longitude' => $allData->selectedOffice[0]->selectedOfficeLng,
                        'user_id' => Auth::user()->id
                    ])->first();
           
            if($recruiterOfficeObj != null){
                $this->saveOffice($recruiterOfficeObj->id, $allData);

                $updatedJob = $this->updateJob($allData->selectedJobType, $allData, $recruiterOfficeObj);
                $this->updateOfficeType($allData, $recruiterOfficeObj->id);
                
                DB::table('recruiter_jobs')->where('id', $allData->jobId)->delete();
            }else{
                $newOfficeObj = $this->updateOffice($allData, config('constants.OneValue'));             
                $updatedJob =  $this->updateJob($allData->selectedJobType, $allData, $newOfficeObj);           
                $this->updateOfficeType($allData, $newOfficeObj['id']);           
                DB::table('recruiter_jobs')->where('id', $allData->jobId)->delete();
                RecruiterOffice::where('id', $allData->selectedOffice[0]->selectedOfficeId)->delete();
            }
            $this->result['data'] = $updatedJob;
            $this->result['success'] = true;
        } catch (\Exception $e) {
            $this->result['success'] = $e->getMessage();
        }
        return $this->result;
    }
    
     /**
     * Method to save office
     * @return view
     */
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
            
        } catch (\Exception $e) {
            $this->result['success'] = $e->getMessage();
        }

        return $this->result;
    }

     /**
     * Method to update office
     * @return view
     */
    private function updateOffice($allData, $requestType = '') {
        try {
            if ($requestType != '') {
                $recruiterOfficeObj = new RecruiterOffice();
                $recruiterOfficeObj->address = $allData->selectedOffice[0]->selectedOfficeAddress;
                $recruiterOfficeObj->latitude = $allData->selectedOffice[0]->selectedOfficeLat;
                $recruiterOfficeObj->user_id = Auth::user()->id;
                $recruiterOfficeObj->longitude = $allData->selectedOffice[0]->selectedOfficeLng;
                $recruiterOfficeObj->zipcode = !empty($allData->selectedOffice[0]->selectedOfficeZipcode) ? $allData->selectedOffice[0]->selectedOfficeZipcode : 0;
                $recruiterOfficeObj->phone_no = $allData->selectedOffice[0]->selectedOfficePhone;
                $recruiterOfficeObj->office_info = $allData->selectedOffice[0]->selectedOfficeInfo;
                $recruiterOfficeObj->save();
            } else {
                $recruiterOfficeObj = RecruiterOffice::where(['id' => $allData->selectedOffice[0]->selectedOfficeId])->first();
            }
         
            $this->saveOffice($recruiterOfficeObj->id, $allData);
          
            $updateOfficeResult = $recruiterOfficeObj;
        } catch (\Exception $e) {
 
            $updateOfficeResult = $e->getMessage();
        }
        return $updateOfficeResult;
    }

     /**
     * Method to update job
     * @return view
     */
    private function updateJob($jobType, $allData, $office = '') {
        try {
            $jobObj = RecruiterJobs::where(['id' => $allData->jobId])->first();
          
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
            $jobObj->preferred_job_location_id = $allData->defaultSelectPreferredJobLocation[0];
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
              
            } elseif ($jobType == config('constants.TemporaryJob')) {
                $jobObj->no_of_jobs = $allData->totalJobOpening;
                $jobObj->pay_rate = $allData->payRate;
                $jobObj->save();
                foreach ($allData->tempJobDates as $tempJobDate) {
                    $newTemJobObj = new TempJobDates();
                    $newTemJobObj->recruiter_job_id = $jobObj['id'];
                    $newTemJobObj->job_date = date('Y-m-d', strtotime($tempJobDate));
                    $newTemJobObj->save();
                    
                }
            } else {
                $jobObj->save();
              
            }
            
        } catch (\Exception $e) {
            $jobObj = $e->getMessage();
        }
        return $jobObj;
    }

     /**
     * Method to update office type
     * @return view
     */
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
            $updateOfficeTypeResult = $e->getMessage();
        }
  
        return $updateOfficeTypeResult;
    }
    
     /**
     * Method to delete job
     * @return view
     */
    public function postDeleteJob(Request $request){
        try{
            $insertData = [];
            $jobId = $request->jobId;
            $jobObj = RecruiterJobs::where('id', $jobId)->first();
            if($jobObj) {
                $jobData = RecruiterJobs::where('recruiter_jobs.id',$jobId)
                                ->select('job_lists.seeker_id', 'job_type', 'job_templates.user_id', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name')
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
                        $message = "Delete job notification | ".$value['office_name']." has deleted the ".strtolower(RecruiterJobs::$jobTypeName[$value['job_type']])." job vacancy for ".$value['jobtitle_name'];
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
                            $params['data']  = ["notificationData"=>$message,
                                "notification_title"=>"Job deleted",
                                "notificationType"=>Notification::OTHER,
                                "type"=>1,
                                "sender_id"=>$senderId
                                ];
                           NotificationServiceProvider::sendPushNotification($deviceModel, $message,$params,$userId);
                        }
                    }
                    if(!empty($insertData)) {
                        Notification::createNotification($insertData);
                    }
                }
                
                JobLists::where('recruiter_job_id', $jobId)->delete();
                JobRatings::where('recruiter_job_id', $jobId)->delete();
                TempJobDates::where('recruiter_job_id', $jobId)->delete();
                //JobseekerTempHired::where('job_id',$jobId)->forceDelete();
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
    
    
     /**
     * Method to check job applied or not
     * @return json
     */
    public function appliedOrNot(CheckJobAppliedOrNotRequest $request){
        try{
            $jobs = RecruiterOffice::join('recruiter_jobs', 'recruiter_jobs.recruiter_office_id' ,'=', 'recruiter_offices.id')
                    ->join('job_lists', 'job_lists.recruiter_job_id' ,'=', 'recruiter_jobs.id')
                    ->where('recruiter_offices.id', $request->officeId)
                    ->whereNull('recruiter_jobs.deleted_at')
                    ->select('job_lists.recruiter_job_id as recruiter_job_id')
                    ->get();
            $this->result['data'] = $jobs;
        } catch (\Exception $e) {
            $this->result['message'] = $e->getMessage();
        }
        return $this->result;
    }
    
     /**
     * Method to view seeker profile
     * @return view
     */
    public function jobSeekerProfile($seekerId) {
        try {
            $seekerDetails = JobSeekerProfiles::getJobSeekerProfile($seekerId);
            return view('web.recuriterJob.seeker-profile',compact('seekerDetails'));

        } catch (\Exception $e) {
            Log::error($e);
            return view('web.error.', ["message" => $e->getMessage()]);
        }
    }

}
