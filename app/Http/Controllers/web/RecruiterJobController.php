<?php

namespace App\Http\Controllers\web;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecruiterJobs;
use App\Models\JobTemplates;
use App\Models\TempJobDates;
use App\Models\RecruiterOffice;

class RecruiterJobController extends Controller
{
    protected $user,$viewData;
    
    public function __construct() {
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->viewData = ['navActive'=>'template',];
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
    
    public function saveOrUpdate(Request $request){
        try{
            $this->validate($request, [
                'templateId' => 'required',
                'dentalOfficeId' => 'required|integer',
                'jobType' => 'required|in:1,2,3',
                'partTimeDays'=>'sometimes',
                'tempDates'=>'sometimes',
                'noOfOpening'=>'sometimes',
                'action' =>'required|in:add,edit',
                'id'=>'integer|required_if:action,edit'
            ]);
            
            $recruiterJobObj = new RecruiterJobs();
            if ($request->action=="edit" && !empty($request->id)) {
                $recruiterJobObj = RecruiterJobs::findById($request->id);
            }
            
            $recruiterJobObj->job_template_id = $request->templateId;
            $recruiterJobObj->recruiter_office_id = $request->dentalOfficeId;
            $recruiterJobObj->job_type = $request->jobType;
            $recruiterJobObj->no_of_jobs = $request->noOfJobs;
            
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
                if(count($request->tempDates)>0){
                    $tempDateArrObj = [];
                    foreach($request->tempDates as $tempDate){
                        $tempDateArrObj[] = new TempJobDates(['jobDate' => $tempDate]);
                    }
                    $recruiterJobObj->tempJobDates()->saveMany($tempDateArrObj);
                    unset($tempDateArrObj);
                }
            }
            unset($recruiterJobObj);
            return redirect('jobtemplates');
        } catch (\Exception $e) {
            return view('web.error.',["message" => $e->getMessage()]);
        }
    }
}
