<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RecruiterJobs;
use App\Models\JobLists;

class ReportsController extends Controller
{
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
    }
    
    public function getReportsPage(){
        return view('web.reports',['activeTab'=>'2']);
    }
    
    public function getReportsTempJobs(){
        try{
            $allTempJobs = RecruiterJobs::getTempJobsReports();
            foreach($allTempJobs as $job){
                $getJob = RecruiterJobs::getIndividualTempJob($job['job_title_id']);
                $job['jobs'] = $getJob;
                foreach($job['jobs'] as $forSeeker){
                    $jobDetails['id'] = $forSeeker['recruiter_job_id'];
                    $jobDetails['job_type'] = $forSeeker['job_type'];
                    $seekers = JobLists::getJobSeekerList($jobDetails, 1);
                    foreach($seekers as $seeker){
                        foreach($seeker as &$seek){
                            $seek['profile_pic'] = url("image/" . 60 . "/" . 60 . "/?src=" .$seek['profile_pic']);
                        }
                    }
                    $forSeeker['seekers'] = $seekers;
                }
            }
            $this->response['data'] = $allTempJobs;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.reports_temp_jobs');
        } catch (\Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
}
