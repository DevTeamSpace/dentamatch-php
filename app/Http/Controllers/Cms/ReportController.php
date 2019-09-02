<?php

namespace App\Http\Controllers\Cms;

use App\Enums\JobAppliedStatus;
use App\Enums\JobType;
use App\Helpers\WebResponse;
use App\Http\Controllers\Controller;
use App\Models\JobseekerTempHired;
use App\Utils\PushNotificationService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\RecruiterJobs;
use App\Models\Device;
use App\Models\Notification;
use App\Models\JobRatings;
use App\Models\TempJobDates;
use App\Models\SavedJobs;
use App\Models\JobLists;
use App\Models\SearchFilter;
use Facades\App\Transformers\JobTransformer;

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
     * List all jobs.
     *
     * @return Response
     */
    public function index()
    {
        return view('cms.report.job-list');
    }

    /**
     * Method to view job details info
     * @param $id
     * @return Response
     */
    public function appliedSeekers($id)
    {
        $jobDetail = RecruiterJobs::join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->select(['recruiter_jobs.id', 'recruiter_jobs.job_type', 'recruiter_jobs.no_of_jobs', 'job_titles.jobtitle_name'])
            ->where('recruiter_jobs.id', '=', $id)
            ->first();
        return view('cms.report.seeker-list', ['jobDetail' => $jobDetail]);
    }

    /**
     * Method to view cancel job seeker list
     * @return Response
     */
    public function cancelLists()
    {
        return view('cms.report.cancel-list');
    }

    /**
     * Method to view jobs list with statistics page
     * GET /cms/report/responselist
     * @return Response
     */
    public function jobResponse()
    {
        return view('cms.report.response-list');
    }

    /**
     * Method to get jobs list with statistics
     * GET AJAX /cms/report/response
     * @return Response
     * @throws \Exception
     */
    public function jobResponseList()
    {
        $jobLists = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->select('recruiter_jobs.id', 'recruiter_jobs.pay_rate', 'recruiter_jobs.job_type', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name')
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

    }

    /**
     * Method to get list of cancel jobs
     * @return Response
     */
    public function listCancel()
    {
        $seekerList = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
            ->join('users', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->where('job_lists.applied_status', JobAppliedStatus::CANCELLED)
            ->select(['jobseeker_profiles.user_id', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name', 'users.email'])
            ->addSelect(DB::raw("count(job_lists.id) as cancelno"))
            ->groupby('jobseeker_profiles.user_id')
            ->orderBy('jobseeker_profiles.first_name', 'asc');

        return Datatables::of($seekerList)
            ->removeColumn('user_id')
            ->make(true);
    }

    /**
     * Method to get list of jobs
     * GET AJAX /report/list
     * @return Response
     * @throws \Exception
     */
    public function jobLists()
    {
        $jobLists = RecruiterJobs::join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->select(['recruiter_jobs.id', 'recruiter_jobs.pay_rate', 'recruiter_jobs.job_type', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name', 'recruiter_offices.address'])
            ->orderBy('recruiter_jobs.id', 'desc');


        return Datatables::of($jobLists)
            ->filterColumn('office_name', function ($query, $keyword) {
                $query->whereRaw("office_name like ?", ["%$keyword%"]);
            })
            ->filterColumn('jobtitle_name', function ($query, $keyword) {
                $query->whereRaw("jobtitle_name like ?", ["%$keyword%"]);
            })
            ->make(true);

    }

    /**
     * Method to get list seekers for a job
     * @return Response
     */
    public function seekerList($id)
    {
        $seekerList = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
            ->where('job_lists.recruiter_job_id', $id)
            ->select(['jobseeker_profiles.user_id', 'job_lists.applied_status', 'jobseeker_profiles.first_name', 'jobseeker_profiles.last_name'])
            ->orderBy('jobseeker_profiles.first_name', 'asc');
        return Datatables::of($seekerList)
            ->removeColumn('user_id')
            ->make(true);
    }

    /**
     * Method to view job location list
     * @return Response
     */
    public function searchJobByLocation()
    {
        return view('cms.report.location-list');
    }

    /**
     * Method to get list location with search count
     * @return Response
     */
    public function searchCountbyLocation()
    {
        $searchList = SearchFilter::select('city')
            ->addSelect(DB::raw("count(id) as searchcount"))
            ->where('city', '!=', "")
            ->groupby('city')
            ->orderBy('city', 'asc')->get();
        return Datatables::of($searchList)
            ->make(true);
    }

    /**
     * Method to download list as csv
     * @return Response
     */
    public function downloadCsv($type)
    {
        $arr = [];
        if ($type == 'cancellist') {
            $data = JobLists::join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'job_lists.seeker_id')
                ->where('job_lists.applied_status', JobAppliedStatus::CANCELLED)
                ->select(['jobseeker_profiles.first_name', 'jobseeker_profiles.last_name'])
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
                if ($val->job_type == JobType::FULLTIME) {
                    $val->job_type = 'Fulltime';
                } else if ($val->job_type == JobType::PARTTIME) {
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

    /**
     * Method to delete job
     * AJAX GET report/delete-job
     * @return Response
     */
    public function getDeleteJob()
    {
        try {
            $insertData = [];
            $jobId = request('jobId');
            $jobObj = RecruiterJobs::where('recruiter_jobs.id', $jobId)
                ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                ->select(['job_templates.user_id', 'recruiter_jobs.job_type', 'job_titles.jobtitle_name'])->first()->toArray();
            if ($jobObj) {
                $jobData = RecruiterJobs::where('recruiter_jobs.id', $jobId)
                    ->select(['job_lists.seeker_id', 'job_type', 'job_templates.user_id', 'job_titles.jobtitle_name', 'recruiter_profiles.office_name'])
                    ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
                    ->join('job_lists', 'job_lists.recruiter_job_id', 'recruiter_jobs.id')
                    ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
                    ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'job_templates.user_id')
                    ->groupby('recruiter_jobs.id')
                    ->groupby('job_lists.seeker_id')
                    ->get();
                $list = $jobData->toArray();
                if (!empty($list)) {
                    $pushList = array_map(function ($value) {
                        return $value;
                    }, $list);
                }
                if (!empty($pushList)) {
                    foreach ($pushList as $value) {
                        $message = "Delete job notification | " . $value['office_name'] . " has deleted the " . strtolower(JobType::ToString($value['job_type'])) . " job vacancy for " . $value['jobtitle_name'];
                        $userId = $value['seeker_id'];
                        $senderId = $value['user_id'];

                        $deviceModel = Device::getDeviceToken($userId);
                        if ($deviceModel) {
                            $insertData[] = ['receiver_id'       => $userId,
                                             'sender_id'         => $senderId,
                                             'notification_data' => $message,
                                             'created_at'        => date('Y-m-d h:i:s'),
                                             'notification_type' => Notification::OTHER,
                            ];
                            $params['data'] = ["notificationData"   => $message,
                                               "notification_title" => "Job deleted",
                                               "notificationType"   => Notification::OTHER,
                                               "type"               => 1,
                                               "sender_id"          => $senderId
                            ];
                            PushNotificationService::send($deviceModel, $message, $params, $userId);
                        }
                    }
                    if (!empty($insertData)) {
                        Notification::createNotification($insertData);
                    }
                }
                //send message to recruiter
                $adminUser = User::getAdminUserDetailsForNotification();
                $message = "Admin has deleted your " . strtolower(JobType::ToString($jobObj['job_type'])) . " job vacancy for " . $jobObj['jobtitle_name'];

                Notification::createNotification(['sender_id'         => $adminUser->id, 'receiver_id' => $jobObj['user_id'],
                                                  'notification_data' => json_encode(['image'   => url('web/images/dentaMatchLogo.png'),
                                                                                      'message' => $message])]);

                JobLists::where('recruiter_job_id', $jobId)->delete();
                JobRatings::where('recruiter_job_id', $jobId)->delete();
                TempJobDates::where('recruiter_job_id', $jobId)->delete();
                JobseekerTempHired::where('job_id', $jobId)->delete();
                RecruiterJobs::where('id', $jobId)->delete();
                SavedJobs::where('recruiter_job_id', $jobId)->delete();
                Notification::where('job_list_id', $jobId)->delete();
            }
            if (!empty(request('requestOrigin'))) {
                Session::flash('message', trans('messages.job_deleted'));
                return redirect('job/lists');
            }
            $result['success'] = true;
            $result['message'] = trans('messages.job_deleted');
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }

    /**
     * GET /cms/report/csvJobs
     */
    public function csvJobs(){
        $list = RecruiterJobs::query()->join('recruiter_offices', 'recruiter_jobs.recruiter_office_id', '=', 'recruiter_offices.id')
            ->join('job_templates', 'job_templates.id', '=', 'recruiter_jobs.job_template_id')
            ->join('job_titles', 'job_titles.id', '=', 'job_templates.job_title_id')
            ->join('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'recruiter_offices.user_id')
            ->leftjoin('job_lists', 'job_lists.recruiter_job_id', '=', 'recruiter_jobs.id')
            ->select('recruiter_profiles.office_name', 'job_titles.jobtitle_name', 'recruiter_jobs.*')
            ->withCount(['tempJobDates as future_temp_dates_count' => function($q){ $q->whereRaw('curdate() <= job_date'); }])
            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 1, 1,0)) AS invited"))
            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 2, 1,0)) AS applied"))
            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 3, 1,0)) AS sortlisted"))
            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 4, 1,0)) AS hired"))
            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 5, 1,0)) AS rejected"))
            ->addSelect(DB::raw("SUM(IF(job_lists.applied_status = 6, 1,0)) AS cancelled"))
            ->groupby('recruiter_jobs.id')
            ->orderBy('recruiter_jobs.id', 'desc')->get();

        $fields = ['office_name', 'job_title', 'job_type', 'pay_rate', 'invited', 'applied', 'sortlisted', 'hired',
                   'rejected', 'cancelled', 'status', 'published_on'];

        $data = JobTransformer::transformAll($list, $fields);

        return WebResponse::csvResponse($data, $fields, 'jobs');
    }

}
