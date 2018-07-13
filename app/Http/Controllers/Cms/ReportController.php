<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\RecruiterJobs;
use Log;
use App\Models\JobLists;
use DB;
use App\Models\SearchFilter;

class ReportController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('cms');
    }

    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    public function index() {
        return view('cms.report.job-list');
    }

    /**
     * Method to view applied job seeker list
     * @return json
     */
    public function appliedSeekers($id) {
        $jobDetail = RecruiterJobs::join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                ->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.no_of_jobs', 'job_titles.jobtitle_name')
                ->where('recruiter_jobs.id', '=', $id)
                ->first();
        return view('cms.report.seeker-list', ['jobDetail' => $jobDetail]);
    }

    /**
     * Method to view cancel job seeker list
     * @return view
     */
    public function cancelLists() {
        return view('cms.report.cancel-list');
    }

    /**
     * Method to view details job response
     * @return view
     */
    public function jobResponse() {
        return view('cms.report.response-list');
    }

    /**
     * Method to get list of jobs with status 
     * @return json
     */
    public function jobResponseList() {
        try {
            $jobLists = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
                            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                            ->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name')
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 1, 1,0)) AS invited"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 2, 1,0)) AS applied"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 3, 1,0)) AS sortlisted"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 4, 1,0)) AS hired"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 5, 1,0)) AS rejected"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 6, 1,0)) AS cancelled"))
                            ->groupby('recruiter_jobs.id')
                            ->orderBy('recruiter_jobs.id', 'desc')->get();
            return Datatables::of($jobLists)
                            ->removeColumn('id')
                            ->make(true);
        } catch (Exception $ex) {
            Log::error($e);
        }
    }

    /**
     * Method to get list of cancel jobs
     * @return json
     */
    public function listCancel() {
        try {
            $seekerList = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                            ->join('users','jobseeker_profiles.user_id','=','users.id')
                            ->where('job_lists.applied_status', JobLists::CANCELLED)
                            ->select('jobseeker_profiles.user_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'users.email')
                            ->addSelect(DB::raw("count(job_lists.id) as cancelno"))
                            ->groupby('jobseeker_profiles.user_id')
                            ->orderBy('jobseeker_profiles.first_name', 'asc');
               
            return Datatables::of($seekerList)
                            ->removeColumn('user_id')
                            ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Method to get list of jobs
     * @return json
     */
    public function jobLists() {
        try {
            $jobLists = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
                            ->select('recruiter_jobs.id', 'recruiter_jobs.job_type', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name', 'recruiter_offices.address')
                            ->orderBy('recruiter_jobs.id', 'desc');
 
            return Datatables::of($jobLists)
                            ->filterColumn('office_name', function ($query, $keyword) {
                                $query->whereRaw("office_name like ?", ["%$keyword%"]);
                            })
                            ->filterColumn('jobtitle_name', function ($query, $keyword) {
                                $query->whereRaw("jobtitle_name like ?", ["%$keyword%"]);
                            })
                            ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Method to get list seekers for a job
     * @return json
     */
    public function seekerList($id) {
        try {
            $seekerList = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                            ->where('job_lists.recruiter_job_id', $id)
                            ->select('jobseeker_profiles.user_id', 'job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name')
                            ->orderBy('jobseeker_profiles.first_name', 'asc');
            return Datatables::of($seekerList)
                            ->removeColumn('user_id')
                            ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Method to view job location list
     * @return view
     */
    public function searchJobByLocation() {
        return view('cms.report.location-list');
    }

    /**
     * Method to get list location with search count
     * @return json
     */
    public function searchCountbyLocation() {
        try {
            $searchList = SearchFilter::select('city')
                            ->addSelect(DB::raw("count(id) as searchcount"))
                            ->where('city', '!=', "")
                            ->groupby('city')
                            ->orderBy('city', 'asc')->get();
            return Datatables::of($searchList)
                            ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Method to download list as csv
     * @return file
     */
    public function downloadCsv($type) {
        $arr = [];
        if ($type == 'cancellist') {
            $data = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                            ->where('job_lists.applied_status', JobLists::CANCELLED)
                            ->select('jobseeker_profiles.first_name', 'jobseeker_profiles.last_name')
                            ->addSelect(DB::raw("count(job_lists.id) as cancelno"))
                            ->groupby('jobseeker_profiles.user_id')
                            ->orderBy('jobseeker_profiles.first_name', 'asc')->get();

            
            $arr['first_name'] = "First Name";
            $arr['last_name'] = 'Last Name';
            $arr['cancelno'] = 'No of cancellation';
        } else if ($type == 'responselist') {
            $data = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
                            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
                            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
                            ->select('recruiter_profiles.office_name', 'job_titles.jobtitle_name', 'recruiter_jobs.job_type')
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 1, 1,0)) AS invited"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 2, 1,0)) AS applied"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 3, 1,0)) AS sortlisted"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 4, 1,0)) AS hired"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 5, 1,0)) AS rejected"))
                            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 6, 1,0)) AS cancelled"))
                            ->groupby('recruiter_jobs.id')
                            ->orderBy('recruiter_jobs.id', 'desc')->get();
            
            foreach ($data as $val) {
                if ($val->job_type == 1) {
                    $val->job_type = 'Fulltime';
                } elseif ($val->job_type == 2) {
                    $val->job_type = 'Parttime';
                } else {
                    $val->job_type = 'Temporary';
                }
            }

            $arr['office_name'] = "Office Name";
            $arr['job_titles'] = "Job Title";
            $arr['job_type'] = "Job Type";
            $arr['invited'] = 'Invited';
            $arr['applied'] = 'Applied';
            $arr['sortlisted'] = 'Interviewing';
            $arr['hired'] = 'Hired';
            $arr['rejected'] = 'Rejected';
            $arr['cancelled'] = 'Cancelled';
        } else {
            $data = SearchFilter::select('city')
                            ->addSelect(DB::raw("count(id) as searchcount"))
                            ->groupby('city')
                            ->orderBy('city', 'asc')->get();
            $arr['city'] = "City";
            $arr['searchcount'] = "Count";
        }
        $list = $data->toArray();

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $type . time() . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $outstream = fopen("php://output", 'r+');

        fputcsv($outstream, $arr, ',', '"');

        if (!empty($list)) {
            foreach ($list as $value) {
                fputcsv($outstream, $value, ',', '"');
            }
        }

        fgets($outstream);
        fclose($outstream);
    }

}
