<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RecruiterJobs;
use App\Models\JobLists;
use App\Models\JobseekerTempHired;
use Log;
use App\Http\Requests\CalenderSeekerRequest;
use App\Models\JobTemplates;
use Auth;

class CalenderController extends Controller
{
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
    }
    
    /**
     * Method to view user data on calendar
     * @return view
     */
    public function getCalender(){
        $userId = Auth::user()->id;
        $jobTemplateModalData = JobTemplates::getAllUserTemplates($userId);
        return view('web.calender',['navActive'=>'calendar', 'jobTemplateModalData' => $jobTemplateModalData]);
    }
    
    /**
     * Method to get list of all temp job data
     * @return json
     */
    public function getCalenderDetails(){
        try{
            $allJobs = RecruiterJobs::getAllTempJobsHired();
            $result  = [];
            foreach($allJobs as $job){
                $tempJobs   =   explode(',', $job['temp_job_dates']);
                foreach ($tempJobs as  $value) {
                    $innerArray = [];
                    $jobDetails['id'] = $job['id'];
                    $jobDetails['job_type'] = $job['job_type'];
                    $jobDetails['job_date'] = $value;
                    
                    if($job['job_type'] == RecruiterJobs::TEMPORARY){
                        $seekers = JobseekerTempHired::getTempJobSeekerList($jobDetails, config('constants.OneValue'));
                    }
                    else{
                        $seekers = JobLists::getJobSeekerList($jobDetails, config('constants.OneValue'));
                    }

                    foreach($seekers as &$seeker){
                        foreach($seeker as &$seek){
                            $seek['profile_pic'] = url("image/" . 60 . "/" . 60 . "/?src=" .$seek['profile_pic']);
                        }
                    }
                    $innerArray =   $job;
                    $innerArray->temp_job_dates = $value;
                    $innerArray['seekers'] = $seekers;
                    $result[] = $innerArray->toArray();
                }
            }

            $this->response['jobs'] = $result;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.calender_details_fetched');
        } catch (\Exception $e) {
            Log::error($e);
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
    /**
     * Method to get list seeker data 
     * @return json
     */
    public function getCalenderSeekers(CalenderSeekerRequest $request){
        try{
            $history = request()->get('historyLoad');
            
            $jobObj = RecruiterJobs::where('id',$request->jobId);
            if($history==true){
                $jobObj->withTrashed();
            }
            $job=$jobObj->first();
            $jobDetails['id'] = $job['id'];
            $jobDetails['job_type'] = $job['job_type'];
            $seekers = JobLists::getJobSeekerList($jobDetails, config('constants.OneValue'));
            $jobDetails['job_date'] = $request->jobDate;
            
            if($job['job_type'] == RecruiterJobs::TEMPORARY)
                $seekers = JobseekerTempHired::getTempJobSeekerList($jobDetails, config('constants.OneValue'));
            else        
                $seekers = JobLists::getJobSeekerList($jobDetails, config('constants.OneValue'));
            
            foreach($seekers as &$seeker){
                foreach($seeker as &$seek){
                    $seek['profile_pic'] = url("image/" . config('constants.Resolution') . "/" . config('constants.Resolution') . "/?src=" .$seek['profile_pic']);
                }
            }
            $this->response['data'] = $seekers;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.calender_seekers_fetched');
        } catch (\Exception $e) {
            Log::error($e);
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
}
