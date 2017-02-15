<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RecruiterOffice;
use App\Models\RecruiterJobs;
use App\Models\JobLists;

class CalenderController extends Controller
{
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function getCalender(){
        return view('web.calender');
    }
    
    public function getCalenderDetails(){
        try{
            $allJobs = RecruiterJobs::getAllTempJobs();
            foreach($allJobs as $job){
                $jobDetails['id'] = $job['id'];
                $jobDetails['job_type'] = $job['job_type'];
                $seekers = JobLists::getJobSeekerList($jobDetails, 1);
                foreach($seekers as &$seeker){
                    foreach($seeker as &$seek){
                        $seek['profile_pic'] = url("image/" . 60 . "/" . 60 . "/?src=" .$seek['profile_pic']);
                    }
                }
                $job['seekers'] = $seekers;
            }
            $this->response['jobs'] = $allJobs;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.calender_details_fetched');
        } catch (\Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
}
