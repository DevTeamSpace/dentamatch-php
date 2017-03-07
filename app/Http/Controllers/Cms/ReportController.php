<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\User;
use App\Models\RecruiterJobs;
use Log;
use App\Models\JobLists;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    public function index()
    {
        return view('cms.report.joblist');
    }
    public function appliedSeekers($id)
    {
        $jobDetail = RecruiterJobs::join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                ->select('recruiter_jobs.id','recruiter_jobs.job_type','recruiter_jobs.no_of_jobs',
                        'job_titles.jobtitle_name')
                ->where('recruiter_jobs.id','=',$id)
                ->first();       
        return view('cms.report.seekerlist',['jobDetail' => $jobDetail]);
    }
    
    public function jobLists(){
        try{
        $jobLists = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                ->join('job_templates','job_templates.id','=','recruiter_jobs.job_template_id')
                ->join('job_titles','job_titles.id', '=' , 'job_templates.job_title_id')
                ->join('recruiter_profiles','recruiter_profiles.user_id', '=' , 'recruiter_offices.user_id')
                ->select('recruiter_jobs.id','recruiter_jobs.job_type',
                        'job_titles.jobtitle_name','recruiter_profiles.office_name',
                        'recruiter_offices.address')
                ->orderBy('recruiter_jobs.id', 'desc')->get();
        
        return Datatables::of($jobLists)
                    ->removeColumn('id')
                    ->addColumn('jobtype', function ($jobLists) {
                            $jobType = "";
                            if($jobLists->job_type == 1){
                                $jobType = 'Fulltime';
                            }else if($jobLists->job_type == 2){
                                $jobType = 'Parttime';
                            }else{
                                $jobType = 'Temporary';
                            }
                        return $jobType;
                    })
                    ->addColumn('address', function ($jobLists) {   
                        return substr($jobLists->address,0,100);
                    })
                    ->addColumn('action', function ($jobLists) {
                        $view = url('cms/report/'.$jobLists->id.'/view');
                        $action = '<a href="'.$view.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> View Details</a>&nbsp;';
                        return $action;

                    })
                    ->make(true);
        }catch (\Exception $e) {
            Log::error($e);
        }
    }
    
    public function seekerList($id){
        try{
        $seekerList = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                ->where('job_lists.recruiter_job_id', $id)
                ->select('jobseeker_profiles.user_id','job_lists.applied_status',
                        'jobseeker_profiles.first_name','jobseeker_profiles.last_name')
                ->orderBy('jobseeker_profiles.first_name', 'asc')->get();
        return Datatables::of($seekerList)
                    ->removeColumn('user_id')
                    ->addColumn('applied_status', function ($seekerList) {
                            $status = "";
                            switch ($seekerList->applied_status) {
                            case JobLists::INVITED:
                                $status = 'Invited';
                                break;
                            case JobLists::APPLIED:
                                $status = 'Applied';
                                break;
                            case JobLists::SHORTLISTED:
                                $status = 'Shortlisted';
                                break;
                            case JobLists::HIRED:
                                $status = 'Hired';
                                break;
                            case JobLists::REJECTED:
                                $status = 'Rejected';
                                break;
                            case JobLists::CANCELLED:
                                $status = 'Cancelled';
                                break;
                            default:
                                $status = 'No Status';
                        }
                        return $status;
                    })
                    ->make(true);
            
        }catch (\Exception $e) {
            Log::error($e);
        }
    }

}
