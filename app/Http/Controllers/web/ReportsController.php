<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\RecruiterJobs;
use App\Models\JobLists;
use App\Http\Requests\ReportSeekersRequest;
use App\Http\Requests\IndividualTempJobRequest;
use Log;

class ReportsController extends Controller
{
    private $response = [];
    
    public function __construct(){
        $this->middleware('auth');
    }
    
     /**
     * Method to view report page
     * @return view
     */
    public function getReportsPage($history){
        return view('web.reports',['activeTab'=>'2','history'=>$history]);
    }
    
     /**
     * Method to view temp job report
     * @return json
     */
    public function getReportsTempJobs(){
        try{
            $history = request()->get('historyLoad');
            $allTempJobs = RecruiterJobs::getTempJobsReports($history);
            $this->response['data'] = $allTempJobs;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.reports_temp_jobs');
        } catch (\Exception $e) {
            Log::error($e);
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
     /**
     * Method to view individual temp job
     * @return json
     */
    public function getIndividualTempJob(IndividualTempJobRequest $request){
        try{
            $history = request()->get('historyLoad');
            $getJob = RecruiterJobs::getIndividualTempJob($request->jobTitleId,$history);
            $this->response['data'] = $getJob;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.individual_report');
        } catch (\Exception $e) {
            Log::error($e);
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
    
     /**
     * Method to view seeker data in reports
     * @return json
     */
    public function getReportSeekers(ReportSeekersRequest $request){
        try{
            $job = RecruiterJobs::where('id',$request->jobId)->first();
            $jobDetails['id'] = $job['id'];
            $jobDetails['job_type'] = $job['job_type'];
            $seekers = JobLists::getJobSeekerList($jobDetails, config('constants.OneValue'));
            foreach($seekers as &$seeker){
                foreach($seeker as &$seek){
                    $seek['profile_pic'] = url("image/" . config('constants.Resolution') . "/" . config('constants.Resolution') . "/?src=" .$seek['profile_pic']);
                }
            }
            $this->response['data'] = $seekers;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.report_seekers');
        } catch (\Exception $e) {
            Log::error($e);
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
}
