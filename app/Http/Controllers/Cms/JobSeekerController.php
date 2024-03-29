<?php

namespace App\Http\Controllers\Cms;

use App\Enums\JobAppliedStatus;
use App\Mail\ResetPassword;
use App\Utils\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use App\Models\Device;
use App\Models\Notification;
use Session;
use App\Models\Affiliation;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserProfile;
use App\Models\PasswordReset;
use App\Models\JobSeekerProfiles;
use App\Models\JobSeekerTempAvailability;
use Mail;
use Log;

class JobSeekerController extends Controller
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
     * Show the form to create a new location.
     *
     * @return Response
     */
    public function create()
    {
        return view('cms.jobseeker.create');
    }

    /**
     * List all locations.
     *
     * @param  array $data
     * @return User
     */
    protected function index()
    {
        return view('cms.jobseeker.index');
    }


    protected function verificationLicense()
    {
        return view('cms.jobseeker.verification-status');
    }

    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $userProfile = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                'users.email', 'users.id', 'users.is_active',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name',
                'users.is_verified'
            )
            ->where('users.id', $id)->first();
        return view('cms.jobseeker.update', ['userProfile' => $userProfile]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        try {
            $reqData = $request->all();
            if (isset($request->id)) {
                $rules['firstname'] = "Required";
                $rules['lastname'] = "Required";
                $rules['email'] = "email|required|Unique:users,email," . $request->id;
                $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                UserProfile::where('user_id', $request->id)->update(['first_name' => $request->firstname, 'last_name' => $request->lastname]);
                $activationStatus = ($request->is_active) ? 1 : 0;
                if ($activationStatus == 0) {
                    Device::unRegisterAll($request->id);
                }
                User::where('id', $request->id)->update(['email' => $request->email, 'is_active' => $activationStatus]);
                $msg = trans('messages.jobseeker_updated_success');
            } else {
                $rules['firstname'] = "Required";
                $rules['lastname'] = "Required";
                $rules['email'] = 'required|email|Unique:users,email';
                $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $reqData = $request->all();
                $user = [
                    'email'       => $reqData['email'],
                    'password'    => '',
                    'is_verified' => 1,
                    'is_active'   => 1,
                ];
                $userId = User::insertGetId($user);
                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = UserGroup::JOBSEEKER;
                $userGroupModel->user_id = $userId;
                $userGroupModel->save();

                $userProfileModel = new UserProfile();
                $userProfileModel->user_id = $userId;
                $userProfileModel->first_name = $reqData['firstname'];
                $userProfileModel->last_name = $reqData['lastname'];
                $userProfileModel->save();

                $token = \Illuminate\Support\Facades\Crypt::encrypt($reqData['email'] . time());
                $passwordModel = PasswordReset::firstOrNew(['user_id' => $userId, 'email' => $reqData['email']]);
                $passwordModel->fill(['token' => $token]);
                $passwordModel->save();

                $url = url('password/reset', ['token' => $token]);
                Mail::to($reqData['email'])->queue(new ResetPassword($reqData['firstname'], $url));

                $msg = trans('messages.jobseeker_added_success');
            }
            Session::flash('message', $msg);
            return redirect('cms/jobseeker/index');
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * Soft delete a location.
     *
     * @param  Location $id
     * @return return to lisitng page
     */
    public function delete($id)
    {
        Affiliation::findOrFail($id)->delete();
        Session::flash('message', trans('messages.location_deleted'));

    }

    /**
     * Method to get list of job seekers
     * @return json
     */
    public function jobSeekerList(Request $request)
    {
        try {
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->leftjoin('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->leftjoin('preferred_job_locations', 'jobseeker_profiles.preferred_job_location_id', '=', 'preferred_job_locations.id')
                ->select(
                    'users.email', 'users.id',
                    'jobseeker_profiles.first_name',
                    'jobseeker_profiles.last_name',
                    'users.is_verified', 'users.is_active',
                    'users.created_at as registered_on',
                    'job_titles.jobtitle_name',
                    'preferred_job_locations.preferred_location_name'
                )
                ->where('user_groups.group_id', UserGroup::JOBSEEKER);
            $startDate = $request->get('startDate');
            $endDate = $request->get('endDate');
            if ($startDate != '') {
                $userData->where('users.created_at', '>=', date('Y-m-d H:i:00', strtotime($startDate)));
            }
            if ($endDate != '') {
                $userData->where('users.created_at', '<=', date('Y-m-d H:i:00', strtotime($endDate)));
            }
            return Datatables::of($userData)
                ->order(function ($query) {
                    $query->orderBy('users.created_at', request()->get('order')[0]['dir']);
                })
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    /**
     * Method to get list of verified jobseekers
     * @return json
     */
    public function jobSeekerVerificationList()
    {
        try {
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->join('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->select(
                    'users.id',
                    'job_titles.jobtitle_name',
                    'jobseeker_profiles.first_name',
                    'jobseeker_profiles.last_name',
                    'jobseeker_profiles.dental_state_board',
                    'jobseeker_profiles.license_number',
                    'jobseeker_profiles.state',
                    'jobseeker_profiles.is_job_seeker_verified'
                )
                ->where('user_groups.group_id', UserGroup::JOBSEEKER)
                ->where('job_titles.is_license_required', 1)
                ->orderBy('users.id', 'desc');
            return Datatables::of($userData)
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function jobSeekerVerificationView($id)
    {
        try {
            $s3Path = env('AWS_URL');
            $s3Bucket = env('AWS_BUCKET');
            $s3Url = $s3Path . DIRECTORY_SEPARATOR . $s3Bucket . DIRECTORY_SEPARATOR;
            $userProfile = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->join('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->select(
                    'users.email', 'users.id', 'users.is_active',
                    'jobseeker_profiles.first_name',
                    'jobseeker_profiles.last_name',
                    'jobseeker_profiles.dental_state_board',
                    'jobseeker_profiles.state',
                    'jobseeker_profiles.license_number',
                    'jobseeker_profiles.is_job_seeker_verified',
                    'job_titles.jobtitle_name'
                )
                ->where('users.id', $id)->first();

            return view('cms.jobseeker.verification-detail', ['userProfile' => $userProfile, 's3Url' => $s3Url]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function storeVerification(Request $request)
    {
        try {
            $msg = trans('messages.something_wrong');
            if (!empty($request->verify)) {
                switch ($request->verify) {
                    case "Approve" :
                        $statusCode = 1;
                        $msg = trans('messages.jobseeker_verification_approved');
                        break;
                    case "Reject" :
                        $statusCode = 2;
                        $msg = trans('messages.jobseeker_verification_reject');
                        break;
                    default :
                        $statusCode = 0;
                        $msg = trans('messages.jobseeker_updated_success');
                        break;
                }
                UserProfile::where('user_id', $request->user_id)->update(['is_job_seeker_verified' => $statusCode]);
                $this->sendPushUser($request->user_id, $request->verify);
            }

            Session::flash('message', $msg);
            return redirect('cms/jobseeker/verification');
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    public function sendPushUser($receiverId, $verificationStatus)
    {
        $user = User::getAdminUserDetailsForNotification();
        if ($verificationStatus == "Approve") {
            $notificationData = [
                'notificationData'   => 'Your license number has been approved by admin',
                'notification_title' => 'Dental License Status',
                'sender_id'          => $user->id,
                'type'               => 1,
                'notificationType'   => Notification::LICENSEACCEPTREJECT,
            ];
        } else if ($verificationStatus == 'Reject') {
            $notificationData = [
                'notificationData'   => 'Your license number has been rejected by admin',
                'notification_title' => 'Dental License Status',
                'sender_id'          => $user->id,
                'type'               => 1,
                'notificationType'   => Notification::LICENSEACCEPTREJECT,
            ];
        }

        $params['data'] = $notificationData;
        $devices = Device::getDeviceToken($receiverId);
        if (!empty($devices)) {
            $insertData = [];
            if ($devices->device_token && strlen($devices->device_token) >= 22) {
                $insertData[] = ['receiver_id'       => $devices->user_id,
                                 'sender_id'         => $user->id,
                                 'notification_data' => $notificationData['notificationData'],
                                 'created_at'        => date('Y-m-d h:i:s'),
                                 'notification_type' => Notification::OTHER,
                ];
            }
            PushNotificationService::send($devices, $notificationData['notificationData'], $params);
        }

        if (!empty($insertData)) {
            Notification::insert($insertData);
        }
    }

    public function jobSeekerDetailView($id)
    {
        try {
            $seekerDetails = JobSeekerProfiles::getJobSeekerProfile($id);
            return view('cms.jobseeker.jobseeker-detail', ['seekerDetails' => $seekerDetails]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * List all unverfied jobseekers.
     *
     * @param  array $data
     * @return User
     */
    protected function unverified()
    {
        return view('cms.jobseeker.unverified');
    }

    /**
     * List all unverfied jobseekers.
     *
     * @param  array $data
     * @return User
     */
    protected function incomplete()
    {
        return view('cms.jobseeker.incomplete');
    }

    public function unverifiedJobseekerList()
    {
        try {
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->leftjoin('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->leftjoin('preferred_job_locations', 'jobseeker_profiles.preferred_job_location_id', '=', 'preferred_job_locations.id')
                ->select(
                    'users.email', 'users.id', 'jobseeker_profiles.created_at',
                    'jobseeker_profiles.first_name', 'jobseeker_profiles.state',
                    'jobseeker_profiles.last_name', 'jobseeker_profiles.license_number',
                    'users.is_verified', 'users.is_active',
                    'job_titles.jobtitle_name',
                    'preferred_job_locations.preferred_location_name'
                )
                ->where('user_groups.group_id', UserGroup::JOBSEEKER)
                ->where('users.is_verified', 0)
                ->orderBy('users.id', 'desc');
            return Datatables::of($userData)
                ->removeColumn('id')
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    public function incompleteJobseekerList()
    {
        try {
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->leftjoin('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->leftjoin('preferred_job_locations', 'jobseeker_profiles.preferred_job_location_id', '=', 'preferred_job_locations.id')
                ->select(
                    'users.email', 'users.id', 'jobseeker_profiles.created_at',
                    'jobseeker_profiles.first_name', 'jobseeker_profiles.license_number',
                    'jobseeker_profiles.last_name', 'jobseeker_profiles.state',
                    'jobseeker_profiles.is_completed', 'users.is_active',
                    'job_titles.jobtitle_name',
                    'preferred_job_locations.preferred_location_name'
                )
                ->where('user_groups.group_id', UserGroup::JOBSEEKER)
                ->where('jobseeker_profiles.is_completed', 0)
                ->orderBy('users.id', 'desc');

            return Datatables::of($userData)
                ->removeColumn('id')
                ->make(true);
        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    public function downloadIncompleteJobseekerCsv()
    {
        $arr = [];
        $data = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                'users.email',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name'
            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->where('jobseeker_profiles.is_completed', 0)
            ->orderBy('users.id', 'desc')->get();


        $arr['email'] = "Email Id";
        $arr['first_name'] = "First Name";
        $arr['last_name'] = 'Last Name';
        $list = $data->toArray();

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=incompleteJobseeker_" . time() . ".csv");
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

    public function downloadUnverifiedJobseekerCsv()
    {
        $arr = [];
        $data = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                'users.email',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name'
            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->where('users.is_verified', 0)
            ->orderBy('users.id', 'desc')->get();


        $arr['email'] = "Email Id";
        $arr['first_name'] = "First Name";
        $arr['last_name'] = 'Last Name';
        $list = $data->toArray();

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=unverifiedJobseeker_" . time() . ".csv");
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

    public function nonAvailableUsers()
    {
        return view('cms.jobseeker.nonavailableusers');
    }

    public function invited()
    {
        return view('cms.jobseeker.invited');
    }

    public function listNonAvailableUsers()
    {
        try {
            $availableUsers = JobSeekerTempAvailability::select('user_id')->groupBy('user_id')->get('user_id');

            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->leftjoin('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->leftjoin('preferred_job_locations', 'jobseeker_profiles.preferred_job_location_id', '=', 'preferred_job_locations.id')
                ->select(
                    'users.email', 'users.id', 'jobseeker_profiles.created_at',
                    'jobseeker_profiles.first_name', 'jobseeker_profiles.license_number',
                    'jobseeker_profiles.last_name', 'jobseeker_profiles.state',
                    'jobseeker_profiles.is_completed', 'users.is_active',
                    'job_titles.jobtitle_name',
                    'preferred_job_locations.preferred_location_name'
                )
                ->where('user_groups.group_id', UserGroup::JOBSEEKER)
                ->whereNotIn('users.id', $availableUsers)
                ->where('is_fulltime', 0)
                ->where('is_parttime_monday', 0)
                ->where('is_parttime_tuesday', 0)
                ->where('is_parttime_wednesday', 0)
                ->where('is_parttime_thursday', 0)
                ->where('is_parttime_friday', 0)
                ->where('is_parttime_saturday', 0)
                ->where('is_parttime_sunday', 0)
                ->orderBy('users.id', 'desc');
            return Datatables::of($userData)
                ->removeColumn('id')
                ->make(true);


        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    public function listInvitedUsers()
    {
        try {
            $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
                ->join('job_lists', 'job_lists.seeker_id', '=', 'users.id')
                ->leftjoin('job_titles', 'job_titles.id', '=', 'jobseeker_profiles.job_titile_id')
                ->leftjoin('preferred_job_locations', 'jobseeker_profiles.preferred_job_location_id', '=', 'preferred_job_locations.id')
                ->select(
                    'users.email', 'users.id', 'jobseeker_profiles.created_at',
                    'users.is_verified', 'users.is_active',
                    'jobseeker_profiles.first_name', 'jobseeker_profiles.license_number',
                    'jobseeker_profiles.last_name', 'jobseeker_profiles.state',
                    'job_titles.jobtitle_name',
                    'preferred_job_locations.preferred_location_name'
                )
                ->where('user_groups.group_id', UserGroup::JOBSEEKER)
                ->where('job_lists.applied_status', JobAppliedStatus::INVITED)
//                        ->whereNotIn('job_lists.applied_status', [2,3,4,5])
                ->whereNull('job_lists.deleted_at')
                ->groupBy('users.id')
                ->orderBy('users.id', 'desc');
            return Datatables::of($userData)
                ->removeColumn('id')
                ->make(true);


        } catch (\Exception $e) {
            Log::error($e);
        }

    }

    public function downloadNonAvailableUsersCsv()
    {
        $arr = [];
        $availableUsers = JobSeekerTempAvailability::select('user_id')
            ->groupBy('user_id')
            ->get('user_id')
            ->map(function ($query) {
                return $query['user_id'];
            })->toArray();

        $data = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->select(
                'users.email',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name'

            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->whereNotIn('users.id', $availableUsers)
            ->where('is_fulltime', 0)
            ->where('is_parttime_monday', 0)
            ->where('is_parttime_tuesday', 0)
            ->where('is_parttime_wednesday', 0)
            ->where('is_parttime_thursday', 0)
            ->where('is_parttime_friday', 0)
            ->where('is_parttime_saturday', 0)
            ->where('is_parttime_sunday', 0)
            ->orderBy('users.id', 'desc')
            ->get();


        $arr['email'] = "Email Id";
        $arr['first_name'] = "First Name";
        $arr['last_name'] = 'Last Name';
        $list = $data->toArray();

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=unavailable_jobseeker_" . time() . ".csv");
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

    public function downloadInvitedUsersCsv()
    {
        $arr = [];
        $data = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
            ->join('jobseeker_profiles', 'jobseeker_profiles.user_id', '=', 'users.id')
            ->join('job_lists', 'job_lists.seeker_id', '=', 'users.id')
            ->select(
                'users.email',
                'jobseeker_profiles.first_name',
                'jobseeker_profiles.last_name'
            )
            ->where('user_groups.group_id', UserGroup::JOBSEEKER)
            ->where('job_lists.applied_status', JobAppliedStatus::INVITED)
//                        ->whereNotIn('job_lists.applied_status', [2,3,4,5])
            ->groupBy('users.id')
            ->orderBy('users.id', 'desc')
            ->get();


        $arr['email'] = "Email Id";
        $arr['first_name'] = "First Name";
        $arr['last_name'] = 'Last Name';
        $list = $data->toArray();

        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=invited_jobseeker_" . time() . ".csv");
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
