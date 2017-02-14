<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RecruiterOffice;
use App\Models\RecruiterJobs;

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
            $jobs = RecruiterJobs::getAllTempJobs();
            $this->response['data'] = $jobs;
            $this->response['success'] = true;
            $this->response['message'] = trans('messages.calender_details_fetched');
        } catch (\Exception $e) {
            $this->response['success'] = false;
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }
}
