<?php

namespace App\Http\Controllers\web;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobRatings;
use Log;

class RatingController extends Controller {

    protected $result;

    public function __construct() {
        $this->middleware('auth');
    }
    
    public function createOrUpdate(Request $request) {
        try{
            $requestData = $request->all();
            $jobId = $requestData['recruiter_job_id'];
            $seekerId = $requestData['seeker_id'];
            $punctuality = !empty($requestData['punctuality']) ? $requestData['punctuality'] : 0;
            $timeManagement = !empty($requestData['time_management']) ? $requestData['time_management'] : 0;
            $skills = !empty($requestData['skills']) ? $requestData['skills'] : 0;
            $teamwork = !empty($requestData['teamwork']) ? $requestData['teamwork'] : 0;
            $onemore = !empty($requestData['onemore']) ? $requestData['onemore'] : 0;
            
            $ratingModel = JobRatings::where('recruiter_job_id', $jobId)
                        ->where('seeker_id', $seekerId)
                        ->first();
            
            if($ratingModel) {
                $punctuality = isset($requestData['punctuality']) ? $requestData['punctuality'] : $ratingModel->punctuality;
                $timeManagement = isset($requestData['time_management']) ? $requestData['time_management'] : $ratingModel->time_management;
                $skills = isset($requestData['skills']) ? $requestData['skills'] : $ratingModel->skills;
                $teamwork = isset($requestData['teamwork']) ? $requestData['teamwork'] : $ratingModel->teamwork;
                $onemore = isset($requestData['onemore']) ? $requestData['onemore'] : $ratingModel->onemore;
            } else {
                $ratingModel = new JobRatings();
                $ratingModel->recruiter_job_id = $jobId;
                $ratingModel->seeker_id = $seekerId;
            }
            
            $ratingModel->punctuality = $punctuality;
            $ratingModel->time_management = $timeManagement;
            $ratingModel->skills = $skills;
            $ratingModel->teamwork = $teamwork;
            $ratingModel->onemore = $onemore;
            $ratingModel->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return redirect()->back();
    }
    
}
