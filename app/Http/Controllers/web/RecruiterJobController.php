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

use DB;

class RecruiterJobController extends Controller
{
    protected $user,$viewData;
    
    public function __construct() {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->viewData = ['navActive'=>'joblisting',];
    }
    
    public function returnView($viewFileName){
        return view('web.recuriterJob.'.$viewFileName,$this->viewData);
    }
    
    public function createJob($templateId){
        try{
            $this->viewData['offices'] = RecruiterOffice::getAllRecruiterOffices(Auth::user()->id);
            $this->viewData['templateId'] = $templateId;
            $this->viewData['jobTemplates'] = JobTemplates::findById($templateId);;
            
            return $this->returnView('create');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
        
    }
    
    public function searchSeekers(Request $request,$jobId){
        try{
            $searchData = $request->all();
            
            $distance = $request->get('distance');
            $availAll = $request->get('avail_all');
            if(empty($distance))
                $distance = JobSeekerProfiles::DISTANCE;
            if(empty($availAll)) {
                $availAll = 0;
            }
            $searchData['distance']     = $distance;
            $searchData['avail_all']    = $availAll;
            $jobDetails     = RecruiterJobs::getRecruiterJobDetails($jobId);
            $seekersList    = JobSeekerProfiles::getJobSeekerProfiles($jobDetails,$searchData);
            //dd($seekersList['paginate']);

            if ($request->ajax()) {
                return view('web.recuriterJob.search', ['seekersList' => $seekersList, 'jobDetails' => $jobDetails, 'searchData' => $searchData])->render();  
            }

            return view('web.recuriterJob.search', compact('seekersList','jobDetails','searchData'));
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }

    public function saveOrUpdate(Request $request){
        $this->validate($request, [
                'templateId' => 'required',
                'dentalOfficeId' => 'required|integer',
                'jobType' => 'required|in:1,2,3',
                'partTimeDays'=>'required_if:jobType,2',
                'tempDates'=>'required_if:jobType,3',
                'noOfJobs'=>'required_if:jobType,3',
                'action' =>'required|in:add,edit',
                'id'=>'integer|required_if:action,edit'
            ]);
            try{
            
            DB::beginTransaction();
            $recruiterJobObj = new RecruiterJobs();
            if ($request->action=="edit" && !empty($request->id)) {
                $recruiterJobObj = RecruiterJobs::findById($request->id);
            }
            
            $recruiterJobObj->job_template_id = $request->templateId;
            $recruiterJobObj->recruiter_office_id = $request->dentalOfficeId;
            $recruiterJobObj->job_type = $request->jobType;
            $recruiterJobObj->no_of_jobs = ($request->noOfJobs!='')?$request->noOfJobs:0;
            
            if($request->jobType==RecruiterJobs::PARTTIME){
                $recruiterJobObj->is_monday = in_array('1',$request->partTimeDays);
                $recruiterJobObj->is_tuesday = in_array('2',$request->partTimeDays);
                $recruiterJobObj->is_wednesday = in_array('3',$request->partTimeDays);
                $recruiterJobObj->is_thursday = in_array('4',$request->partTimeDays);
                $recruiterJobObj->is_friday = in_array('5',$request->partTimeDays);
                $recruiterJobObj->is_saturday = in_array('6',$request->partTimeDays);
                $recruiterJobObj->is_sunday = in_array('7',$request->partTimeDays);
            }
            $recruiterJobObj->save();
            if($request->jobType==RecruiterJobs::TEMPORARY){
                $tempDates = explode(',',$request->tempDates);
                if(count($tempDates)>0){
                    $tempDateArrObj = [];
                    foreach($tempDates as $tempDate){
                        $tempDateArrObj[] = new TempJobDates(['jobDate' => date('Y-m-d',strtotime($tempDate))]);
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
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function listJobs(Request $request) {
        try{
            $this->viewData['jobList'] = RecruiterJobs::getJobs();
            
            if ($request->ajax()) {
                return view('web.recuriterJob.jobData', ['jobList' => $this->viewData['jobList']])->render();  
            }
            
            return $this->returnView('list');
            
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    
    public function editJob(Request $request,$jobId) {
        try{
            dd('In progress');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function jobDetails(Request $request,$jobId) {
        try{
            $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);
            $this->viewData['skills'] = TemplateSkills::getTemplateSkills($this->viewData['job']['job_template_id']);
            $this->viewData['seekerList'] = JobLists::getJobSeekerList($this->viewData['job']);
            return $this->returnView('view');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }

    
    public function updateStatus(Request $request) {
        $this->validate($request, [
                'jobId' => 'required|integer',
                'seekerId' => 'required|integer',
                'appliedStatus' => 'required|integer',
            ]);
        try{
            $requestData = $request->all();
            $jobData = JobLists::getJobInfo($requestData['seekerId'],$requestData['jobId']);
            if($jobData){
                $jobData->applied_status = $requestData['appliedStatus'];
                $jobData->save();
                if($requestData['appliedStatus']==JobLists::SHORTLISTED || $requestData['appliedStatus']==JobLists::HIRED){
                    $userChat = new ChatUserLists();
                    $userChat->recruiter_id = Auth::id();
                    $userChat->seeker_id = $jobData->seeker_id;
                    $userChat->checkAndSaveUserToChatList();
                    $this->sendPushUser($requestData['appliedStatus'],Auth::user()->id,$jobData->seeker_id,$requestData['jobId']);
                }
                
                return redirect('job/details/'.$requestData['jobId']);
            }
        } catch (\Exception $e) {
            dd($e);
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }        


    public function jobSeekerDetails($seekerId, $jobId){
        try{
            $this->viewData['job'] = RecruiterJobs::getRecruiterJobDetails($jobId);

            $seekerDetails = JobSeekerProfiles::getJobSeekerDetails($seekerId,$this->viewData['job']);
            
            return view('web.recuriterJob.seekerdetails',compact('seekerDetails'));

        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
    
    public function sendPushUser($jobstatus,$sender,$receiverId,$jobId){
        $jobDetails = RecruiterJobs::getRecruiterJobDetails($jobId);
        if($jobstatus == JobLists::SHORTLISTED){
            $notificationData = array(
                    'notificationData' => $jobDetails['office_name']." has accepted your invitation for ".$jobDetails['jobtitle_name'],
                    'notification_title'=>'User shortlisted',
                    'sender_id' => $sender,
                    'type' => 1,
                    'notificationType' => Notification::ACCEPTJOB,
                );
        } else if($jobstatus == JobLists::HIRED){
            $notificationData = array(
                    'notificationData' => "You have been hired for  ".$jobDetails['jobtitle_name'],
                    'notification_title'=>'User hired',
                    'sender_id' => $sender,
                    'type' => 1,
                    'notificationType' => Notification::HIRED,
                );
        }
        $data = ['receiver_id'=>$receiverId,'job_list_id' => $jobId,'sender_id' => $sender, 'notification_data'=>$notificationData['notificationData'],'notification_type' => $jobstatus];
        $notificationDetails = Notification::create($data);
        $notificationData['id'] = $notificationDetails->id;
        $notificationData['receiverId'] = $receiverId;
        $notificationData['senderId'] = $sender;
        $params['data'] = $notificationData;
        $params['jobDetails'] = $jobDetails;
        $params['notification_details'] = $notificationDetails;
        $deviceModel = Device::getDeviceToken($receiverId);
        if($deviceModel) {
            //$this->info($userId);
            NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['notificationData'], $params);

            
        }
    
    }
    
    public function jobEdit(Request $request, $jobId){
        return view('web.recuriterJob.edit', compact('jobId'));
    }
    
    public function jobEditDetails(Request $request){
        try{
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
}