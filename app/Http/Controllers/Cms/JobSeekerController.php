<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\Affiliation;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserProfile;
use App\Models\PasswordReset;
use Mail;
use App\Providers\NotificationServiceProvider;
use App\Models\Device;
use App\Models\Notification;


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
     * @param  array  $data
     * @return User
     */
    protected function index()
    {  
        return view('cms.jobseeker.index');
    }
    
    /**
     * Show the form to update an existing location.
     *
     * @return Response
     */
    public function edit($id)
    {
        $userProfile = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.email','users.id','users.is_active',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'users.is_verified'
                                )
                        ->where('users.id', $id)->first();
        return view('cms.jobseeker.update',['userProfile'=>$userProfile]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        if(isset($request->id)){
            $rules['firstname'] = "Required";
            $rules['lastname'] = "Required";
            $this->validate($request, $rules);
            UserProfile::where('user_id', $request->id)->update(['first_name' => $request->firstname,'last_name' => $request->lastname]);
            $activationStatus  = ($request->is_active)?1:0;
            User::where('id',$request->id)->update(['is_active' => $activationStatus]);
            $msg = trans('messages.jobseeker_updated_success');
        }
        else{
            $rules['firstname'] = "Required";
            $rules['lastname'] = "Required";
            $rules['email'] = 'required|email|Unique:users,email';
            $this->validate($request, $rules);
            $reqData = $request->all();
            $user =  array(
                'email' => $reqData['email'],
                'password' => '',
                'is_verified' => 1,
                'is_active' => 1,
            );
            $userId = User::insertGetId($user);
            $userGroupModel = new UserGroup();
            $userGroupModel->group_id = 3;
            $userGroupModel->user_id = $userId;
            $userGroupModel->save();
            
            $userProfileModel = new UserProfile();
            $userProfileModel->user_id = $userId;
            $userProfileModel->first_name = $reqData['firstname'];
            $userProfileModel->last_name = $reqData['lastname'];
            $userProfileModel->save();
            
            $token = md5($reqData['email'] . time());
            $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userId, 'email' => $reqData['email']));
            $passwordModel->fill(['token' => $token]);
            $passwordModel->save();

            Mail::queue('email.resetPasswordToken', ['name' => $reqData['firstname'], 'url' => url('password/reset', ['token' => $token]), 'email' => $reqData['email']], function($message) use ($reqData) {
                $message->to($reqData['email'], $reqData['firstname'])->subject('Set Password Email');
            });
            $msg = trans('messages.jobseeker_added_success');
        }
        
        
            
        Session::flash('message',$msg);
        return redirect('cms/jobseeker/index');
    }
    
    /**
     * Soft delete a location.
     *
     * @param  Location  $id
     * @return return to lisitng page
     */
    public function delete($id){
        Affiliation::findOrFail($id)->delete();
        Session::flash('message',trans('messages.location_deleted'));
        
    }

    public function jobSeekerList(){
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.email','users.id',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'users.is_verified'
                                )
                        ->where('user_groups.group_id', 3)
                        ->orderBy('users.id', 'desc');
        return Datatables::of($userData)
                ->removeColumn('id')
                ->addColumn('active', function ($userData) {
                	$active = ($userData->is_verified == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($userData) {
                    $edit = url('cms/jobseeker/'.$userData->id.'/edit');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    return $action;
                       
                })
                ->make(true);
                       
    }
    public function sendPushAndroid(){
        $userId = 53;
        $notificationData = array(
                    'message' => "The profile completion is still pending.",
                    'notification_title'=>'Profile Completion Reminder',
                    'sender_id' => "",
                    'type' => 1
                );
        $params['data'] = $notificationData;
        //NotificationServiceProvider::sendPushAndroid('fNGa2LzJ4p4:APA91bFKozuiRnK20e5R7lmdyr3vd7ycpC-Ji_PqTdcpUm3yWL3wa5ogc0OOalhE_VPhErXP3oWPnSCf3HtfZvIy', $notificationData['message'], $params);
        $notificationData['receiver_id'] = $userId;
        $params['data'] = $notificationData;
        $deviceModel = Device::getDeviceToken($userId);
        if($deviceModel) {
            NotificationServiceProvider::sendPushNotification($deviceModel, $notificationData['message'], $params);
            $data = ['receiver_id'=>$userId, 'notification_data'=>$notificationData['message']];
            Notification::createNotification($data);
        }
        echo 'test';
    }
    
    public function jobSeekerVerificationList(){
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.id',
                                'jobseeker_profiles.dental_state_board',
                                'jobseeker_profiles.license_number',
                                'jobseeker_profiles.is_job_seeker_verified'
                                )
                        ->where('user_groups.group_id', 3)
                        ->orderBy('users.id', 'desc');
        return Datatables::of($userData)
                ->removeColumn('id')
                ->addColumn('dental_state_board', function ($userData) {
                	$dentalStateBoard = !empty($userData->dental_state_board) ? $userData->dental_state_board :'N/A';
                    return $dentalStateBoard;
                })
                ->addColumn('license_number', function ($userData) {
                	$licenseNumber = !empty($userData->license_number) ? $userData->license_number : "N/A";
                    return $licenseNumber;
                })
                ->addColumn('is_job_seeker_verified', function ($userData) {
                        $statusCode = ['0'=>"Not Verified", '1'=>'Approved', '2'=>'Rejected'];
                	$isVerified = $statusCode[$userData->is_job_seeker_verified];
                    return $isVerified;
                })
                ->addColumn('action', function ($userData) {
                    $edit = url('cms/jobseeker/'.$userData->id.'/verification');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> View</a>&nbsp;';
                    return $action;
                       
                })
                ->make(true);
                       
    }
    
    public function jobSeekerVerificationView($id) {
        $s3Path = env('AWS_URL');
        $s3Bucket = env('AWS_BUCKET');
        $s3Url = $s3Path.DIRECTORY_SEPARATOR.$s3Bucket.DIRECTORY_SEPARATOR;
        $userProfile = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.email','users.id','users.is_active',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'jobseeker_profiles.dental_state_board',
                                'jobseeker_profiles.license_number'
                                )
                        ->where('users.id', $id)->first();
        
        return view('cms.jobseeker.verificationDetail',['userProfile'=>$userProfile, 's3Url' => $s3Url]);
    }
    
    public function storeVerification(Request $request)
    {
        $msg = trans('messages.something_wrong');
        if(!empty($request->verify)) {
            
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
        }
            
        Session::flash('message',$msg);
        return redirect('cms/jobseeker/index');
    }
}
