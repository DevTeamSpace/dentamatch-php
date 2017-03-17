<?php

namespace App\Http\Controllers\web;

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
    
    public function getFavJobseeker(Request $request) {
        $userId = Auth::user()->id;
        $rating = "(avg(job_ratings.punctuality) + avg(job_ratings.time_management) + avg(job_ratings.skills) + avg(job_ratings.teamwork) + avg(job_ratings.onemore))/5";
        
        $favJobSeeker = Favourite::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'favourites.seeker_id')
                ->leftjoin('job_lists', 'favourites.seeker_id', '=', 'job_lists.seeker_id')
                ->leftjoin('job_ratings', 'favourites.seeker_id', '=', 'job_ratings.seeker_id')
                ->leftjoin('job_titles','job_titles.id', '=' , 'jobseeker_profiles.job_titile_id')
                ->where('favourites.recruiter_id', Auth::user()->id)
                ->select(DB::raw('(avg(job_ratings.punctuality) + avg(job_ratings.time_management) + avg(job_ratings.skills))/3 as sum'), 'jobseeker_profiles.user_id as seeker_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'job_lists.applied_status','job_titles.jobtitle_name', 'jobseeker_profiles.profile_pic')
                ->groupby('favourites.seeker_id')
                ->simplePaginate(15);
        
        
        $jobDetail = JobTemplates::join('recruiter_jobs', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_templates.job_title_id', '=', 'job_titles.id')
                ->where('recruiter_jobs.job_type', '3')
                ->where('job_templates.user_id', Auth::user()->id)
                ->select('job_titles.jobtitle_name', 'recruiter_jobs.id as recruiterId')
                ->get();
        
        $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
        return view('web.fav_jobseeker', ['favJobSeeker' => $favJobSeeker, 'jobDetail' => $jobDetail, 'jobTemplateModalData' => $jobTemplateModalData, 'navActive'=>'favseeker']);
    }

    public function postInviteJobseeker(Request $request) {
        $validator = Validator::make($request->all(), [
                    'selectJobSeeker' => 'required',
                    'seekerId' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('fav_jobseeker')
                            ->withErrors($validator)
                            ->withInput();
        }
        try {
            $jobList = \App\Models\JobLists::where('seeker_id', $request->seekerId)
                    ->where('recruiter_job_id',$request->selectJobSeeker)
                    ->whereIn('applied_status',[JobLists::INVITED,JobLists::APPLIED,JobLists::SHORTLISTED])
                    ->first();
            if (isset($jobList) && !empty($jobList)) {
                //if ($jobList->recruiter_job_id != $request->selectJobSeeker) {
                    /*\App\Models\JobLists::create([
                        'recruiter_job_id' => $request->selectJobSeeker,
                        'seeker_id' => $request->seekerId,
                        'applied_status' => '1',
                    ]);*/
                    \App\Models\JobLists::where('id', $jobList->id)->update(['applied_status' => JobLists::INVITED]);
                    $this->sendPushUser(JobLists::INVITED, Auth::user()->id, $request->seekerId, $request->selectJobSeeker);
                    Session::flash('message', trans('messages.invite_sent_success'));
                //}
            } else {
                \App\Models\JobLists::create([
                    'recruiter_job_id' => $request->selectJobSeeker,
                    'seeker_id' => $request->seekerId,
                    'applied_status' => JobLists::INVITED,
                ]);
                $this->sendPushUser(JobLists::INVITED, Auth::user()->id, $request->seekerId, $request->selectJobSeeker);
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
    
    public function sendPushUser($jobstatus, $sender, $receiverId, $jobId) {
        $jobDetails = RecruiterJobs::getRecruiterJobDetails($jobId);
        if ($jobstatus == JobLists::INVITED) {
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

}
