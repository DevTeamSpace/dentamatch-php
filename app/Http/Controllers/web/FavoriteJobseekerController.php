<?php

namespace App\Http\Controllers\web;

use App\Enums\JobAppliedStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use Exception;
use DB;
use App\Models\JobTemplates;
use App\Models\Favourite;
use App\Models\Notification;
use App\Providers\NotificationServiceProvider;
use App\Models\JobLists;
use App\Models\RecruiterJobs;
use App\Models\Device;
use Session;

class FavoriteJobseekerController extends Controller {
    
    /**
     * Method to get favorite list of seekers
     * @return json
     */
    public function getFavJobseeker(Request $request) {
        $userId = Auth::user()->id;
        
        $favJobSeeker = Favourite::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'favourites.seeker_id')
                ->leftjoin('job_lists', 'favourites.seeker_id', '=', 'job_lists.seeker_id')
                ->leftjoin('job_ratings', 'favourites.seeker_id', '=', 'job_ratings.seeker_id')
                ->leftjoin('job_titles','job_titles.id', '=' , 'jobseeker_profiles.job_titile_id')
                ->where('favourites.recruiter_id', Auth::user()->id)
                ->select(DB::raw('(avg(job_ratings.punctuality) + avg(job_ratings.time_management) + avg(job_ratings.skills))/3 as sum'), 'jobseeker_profiles.user_id as seeker_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'job_lists.applied_status','job_titles.jobtitle_name','jobseeker_profiles.profile_pic')
                ->addSelect(DB::raw("avg(punctuality) as punctuality"),DB::raw("avg(time_management) as time_management"),
                           DB::raw("avg(skills) as skills"))
                ->groupby('favourites.seeker_id')
                ->simplePaginate(15);
        
        $jobDetail = JobTemplates::join('recruiter_jobs', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
                ->leftJoin('temp_job_dates',function($query){
                    $query->on('temp_job_dates.recruiter_job_id','=','recruiter_jobs.id')
                    ->whereDate('temp_job_dates.job_date','>=',date('Y-m-d').' 00:00:00');
                })
                ->where('recruiter_jobs.job_type', '3')
                ->where('job_templates.user_id', Auth::user()->id)
                ->whereNull('recruiter_jobs.deleted_at')
                ->select('job_titles.jobtitle_name', 'recruiter_jobs.id as recruiterId',DB::raw("group_concat(distinct(temp_job_dates.job_date) ORDER BY temp_job_dates.job_date ASC) AS temp_job_dates"))
                ->orderby('temp_job_dates','ASC')
                ->groupby('temp_job_dates.recruiter_job_id')
                ->get();
        
        $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
        return view('web.fav-jobseeker', ['favJobSeeker' => $favJobSeeker, 'jobDetail' => $jobDetail, 'jobTemplateModalData' => $jobTemplateModalData, 'navActive'=>'favseeker']);
    }

    /**
     * Method to send invite to favorite job seeker
     * @return json
     */
    public function postInviteJobseeker(Request $request) {
        $validator = Validator::make($request->all(), [
                    'selectJobSeeker' => 'required',
                    'seekerId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('favorite-jobseeker')
                            ->withErrors($validator)
                            ->withInput();
        }
        try {
            $jobList = JobLists::where('seeker_id', $request->seekerId)
                    ->where('recruiter_job_id',$request->selectJobSeeker)
                    ->whereIn('applied_status',[JobAppliedStatus::INVITED,JobAppliedStatus::APPLIED,JobAppliedStatus::SHORTLISTED,JobAppliedStatus::HIRED])
                    ->orderBy('id', 'DESC')
                    ->first();
            
            if (isset($jobList) && !empty($jobList)) {
             
                $message = "";
                if($jobList->applied_status == JobAppliedStatus::INVITED){
                    $message = trans('messages.seeker_already_invited');
                }else if($jobList->applied_status == JobAppliedStatus::APPLIED){
                    $message = trans('messages.seeker_already_applied');
                }else if($jobList->applied_status == JobAppliedStatus::SHORTLISTED){
                    $message = trans('messages.seeker_already_shortlisted');
                }else if($jobList->applied_status == JobAppliedStatus::HIRED){
                    $message = trans('messages.seeker_already_hired');
                }
                Session::flash('message', $message);
            } else {
                JobLists::create([
                    'recruiter_job_id' => $request->selectJobSeeker,
                    'seeker_id' => $request->seekerId,
                    'applied_status' => JobAppliedStatus::INVITED,
                ]);
                $this->sendPushUser(JobAppliedStatus::INVITED, Auth::user()->id, $request->seekerId, $request->selectJobSeeker);
                Session::flash('message', trans('messages.invite_sent_success'));
            }
            return redirect('favorite-jobseeker');
        } catch (Exception $ex) {
            print_r($ex->getMessage());
        }
    }
    
    /**
     * Mark/UnMark as Favourite
     * @param type $seekerId
     * @return int
     */
    public function getMarkFavourite($seekerId)
      {
        $return=[];
        $recruiterId = Auth::user()->id;
        $favModel = Favourite::where('recruiter_id', $recruiterId)->where('seeker_id', $seekerId)->first();
        if($favModel) {
            $return['isFavourite'] = "No";
            $return['seekerId'] = $seekerId;
            $return['success'] = 1;
            $favModel->forceDelete();
        } else {
            $favModel = new Favourite();
            $favModel->recruiter_id = $recruiterId;
            $favModel->seeker_id = $seekerId;
            $favModel->save();
            $return['isFavourite'] = "Yes";
            $return['seekerId'] = $seekerId;
            $return['success'] = 1;   
        }
        
        return $return;
      }
    
      /**
     * Method to send push notification
     * @return json
     */
    public function sendPushUser($jobstatus, $sender, $receiverId, $jobId) {
              
        $jobDetails = RecruiterJobs::getRecruiterJobDetails($jobId);
        if ($jobstatus == JobAppliedStatus::INVITED) {
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
        }
    }
    
      /**
     * Method to favorite seeker job list
     * @return json
     */
    public function postFavouriteJobList(Request $request){
        $rejectedJobs = JobLists::where('seeker_id', '=', $request->userId)->whereIn('applied_status', [JobAppliedStatus::HIRED, JobAppliedStatus::INVITED])->get();
        $rejectedJobsArray = array();      
        if($rejectedJobs){
                    $rejectedJobsData = $rejectedJobs->toArray();
                    $rejectedJobsArray = array_map(function ($value) {
                        return  $value['recruiter_job_id'];
                    }, $rejectedJobsData);
                } 
        
        $jobDetail = JobTemplates::join('recruiter_jobs', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
                ->leftJoin('temp_job_dates',function($query){
                    $query->on('temp_job_dates.recruiter_job_id','=','recruiter_jobs.id')
                    ->whereDate('temp_job_dates.job_date','>=',date('Y-m-d').' 00:00:00');
                })
                ->where('recruiter_jobs.job_type', '3')
                ->where('job_templates.user_id', Auth::user()->id)
                ->whereNull('recruiter_jobs.deleted_at')
                ->select('job_titles.jobtitle_name', 'recruiter_jobs.id as recruiterId',DB::raw("group_concat(distinct(temp_job_dates.job_date) ORDER BY temp_job_dates.job_date ASC) AS temp_job_dates"))
                ->orderby('temp_job_dates','ASC')
                ->groupby('temp_job_dates.recruiter_job_id');
                
        if(count($rejectedJobsArray) > 0){
                $jobDetail->whereNotIn('recruiter_jobs.id',$rejectedJobsArray);
            }
        $returnData = "";
        $listJobs = $jobDetail->get();
        if($listJobs->count() > 0){
            $output = "";
                foreach($listJobs as $job){
                                    $dates_are = '';
                                    if(!empty($job->temp_job_dates))
                                    {
                                    $temp_jobs = (!empty($job->temp_job_dates)?explode(',', $job->temp_job_dates):array());
                                    $dates_are .= (isset($temp_jobs[0])?date('M d, Y',  strtotime($temp_jobs[0])):"");
                                    $dates_are .= (isset($temp_jobs[1])?", ".date('M d, Y',  strtotime($temp_jobs[1])):"");
                                    $dates_are .= (isset($temp_jobs[2])?" , ..":"");
                                    $output .= '<option value="'.$job->recruiterId.'" data-content = "<h5>'.$job->jobtitle_name.'</h5><span class = \'label label-warning\' >Temporary</span> '.$dates_are.'">'.$job->jobtitle_name.'</option>';
                                    $output .= '<option data-divider="true"></option>';
                                    }
                }
                if($output != ""){
                    $returnData = '<option value="" disabled selected>Select </option>'.$output;
                }
            
        }
        return $returnData;
    }

}
