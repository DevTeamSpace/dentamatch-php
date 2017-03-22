<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use App\Providers\NotificationServiceProvider;
use App\Models\Device;
use App\Models\Notification;
use Session;
use App\Models\Affiliation;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserProfile;
use App\Models\PasswordReset;
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
        try{
        $reqData = $request->all();
        if(isset($request->id)){
            $rules['firstname'] = "Required";
            $rules['lastname'] = "Required";
            $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
            UserProfile::where('user_id', $request->id)->update(['first_name' => $request->firstname,'last_name' => $request->lastname]);
            $activationStatus  = ($request->is_active)?1:0;
            User::where('id',$request->id)->update(['is_active' => $activationStatus]);
            $msg = trans('messages.jobseeker_updated_success');
        }
        else{
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
        }catch (\Exception $e) {
            Log::error($e);
        }
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
        try{
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.email','users.id',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
                                'users.is_verified','users.is_active'
                                )
                        ->where('user_groups.group_id', 3)
                        ->orderBy('users.id', 'desc');
        return Datatables::of($userData)
                ->removeColumn('id')
                ->addColumn('active', function ($userData) {
                	$active = ($userData->is_active == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($userData) {
                    $edit = url('cms/jobseeker/'.$userData->id.'/edit');
                    $resetPassword = url('cms/recruiter/'.$userData->id.'/adminResetPassword');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    $action .= '<a href="'.$resetPassword.'"  class="btn btn-xs btn-primary">Reset Password</a>&nbsp;';
                    return $action;
                       
                })
                ->make(true);
        }catch (\Exception $e) {
            Log::error($e);
        }
                       
    }
    
    public function jobSeekerVerificationList(){
        try{
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->join('jobseeker_profiles','jobseeker_profiles.user_id' , '=','users.id')
                        ->select(
                                'users.id',
                                'jobseeker_profiles.first_name',
                                'jobseeker_profiles.last_name',
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
        }catch (\Exception $e) {
            Log::error($e);
        }              
    }
    
    public function jobSeekerVerificationView($id) {
        try{
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
        }catch (\Exception $e) {
            Log::error($e);
        } 
    }
    
    public function storeVerification(Request $request)
    {
        try{
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
            $this->sendPushUser($request->user_id,$request->verify);
        }
            
        Session::flash('message',$msg);
        return redirect('cms/jobseeker/index');
        }catch (\Exception $e) {
            Log::error($e);
        } 
    }
    
    public function sendPushUser( $receiverId, $verificationStatus) {
        $user = User::getAdminUserDetailsForNotification();
        if ($verificationStatus == "Approve") {
            $notificationData = array(
                'notificationData' => 'Your dental state board and license number approved by admin',
                'notification_title' => 'Dental certificate verified',
                'sender_id' => $user->id,
                'type' => 1,
                'notificationType' => Notification::OTHER,
            );
        } else if ($verificationStatus == 'Reject') {
            $notificationData = array(
                'notificationData' => 'Your dental state board and license number rejected by admin',
                'notification_title' => 'Dental certificate rejected',
                'sender_id' => $user->id,
                'type' => 1,
                'notificationType' => Notification::OTHER,
            );
        }

        $params['data'] = $notificationData;
<<<<<<< HEAD
        $device = Device::getDeviceToken($receiverId);
        
        if(!empty($device)) {
            
            $insertData = [];
            if(!empty($device)) {
                if ($device->device_token && strlen($device->device_token) >= 22) {
                    $insertData[] = ['receiver_id'=>$device->user_id,
                        'sender_id'=>$user->id,
                        'notification_data'=>$notificationData['notificationData'],
                        'created_at'=>date('Y-m-d h:i:s'),
                        'notification_type' => Notification::OTHER,
                        ];
                }
                NotificationServiceProvider::sendPushNotification($device, $notificationData['notificationData'], $params);
            }
            if(!empty($insertData)){
                Notification::insert($insertData);
=======
        $devices = Device::getDeviceToken($receiverId);
        if(!empty($devices)) {
            $insertData = [];
            if ($devices->device_token && strlen($devices->device_token) >= 22) {
                $insertData[] = ['receiver_id'=>$devices->user_id,
                    'sender_id'=>$user->id,
                    'notification_data'=>$notificationData['notificationData'],
                    'created_at'=>date('Y-m-d h:i:s'),
                    'notification_type' => Notification::OTHER,
                    ];
>>>>>>> d93c6282eb6c3ea985efb442195a0a29c679efaf
            }
            NotificationServiceProvider::sendPushNotification($devices, $notificationData['notificationData'], $params);
        }
            
        if(!empty($insertData)) {
            Notification::insert($insertData);
        }
    }
}
