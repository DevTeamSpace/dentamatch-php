<?php

namespace App\Http\Controllers\Cms;

use App\Mail\ResetPassword;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator;
use App\Models\RecruiterProfile;
use Mail;
use Log;

class RecruiterController extends Controller
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
     * Show the form to create a new recruiter.
     *
     * @return Response 
     */
    public function create()
    {
        return view('cms.recruiter.create');
    }
    
    /**
     * List all recruiter.
     *
     * @param  array  $data
     * @return User
     */
    public function index()
    {  
        return view('cms.recruiter.index');
    }
    
    /**
     * Show the form to update an existing recruiter.
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->select(
                                'users.email','users.id','users.is_active'
                                )
                        ->where('users.id', $id)->first();
        return view('cms.recruiter.update',['userProfile'=>$user]);
    }

    /**
     * Store a new/update recruiter.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        try 
        {
            $reqData = $request->all();
            if(isset($request->id)){
                $rules['email'] = "email|required|Unique:users,email,".$request->id;
                
                $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
                $activationStatus  = isset($request->is_active) ? 1 : 0;

                User::where('id',$request->id)->update(['email'=>$request->email,'is_active' => $activationStatus]);
                $msg = trans('messages.recruiter_updated_success');
            }
            else
            {
                $rules['email'] = "email|required|unique:users";
                $validator = Validator::make($reqData, $rules);
        
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
                
                $user =  ['email' => $reqData['email'], 'password' => '',
                                'is_verified' => 1, 'is_active' => 1];

                $userId = User::insertGetId($user);
                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = 2;
                $userGroupModel->user_id = $userId;
                $userGroupModel->save();

                $token = \Illuminate\Support\Facades\Crypt::encrypt($reqData['email'] . time());
                $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userId, 'email' => $reqData['email']));
                $passwordModel->fill(['token' => $token]);
                $passwordModel->save();
                
                RecruiterProfile::create(['user_id' => $userId]);

                $url = url('password/reset', ['token' => $token]);
                Mail::to($reqData['email'])->queue(new ResetPassword('Recruiter', $url));

                $msg = trans('messages.recruiter_added_success');
            }

            Session::flash('message',$msg);
        return redirect('cms/recruiter/index');
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
    
    /**
     * Soft delete a recruiter.
     *
     * @param  recruiter  $id
     * @return return to lisitng page
     */
    public function delete($id){
        User::where('id',$id)->update(['is_active'=>0]);

        Session::flash('message',trans('messages.recruiter_deleted'));
        return redirect('cms/recruiter/index');
        
    }
    
    /**
     * Method to get list of recruiter
     * @return json
     */
    public function recruiterList(){
        try{
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->leftjoin('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'users.id')
                        ->select(
                                'recruiter_profiles.office_name',
                                'users.email','users.id',
                                'users.is_active'
                                )
                        ->where('user_groups.group_id', 2)
                        ->orderBy('users.id', 'desc');
        return Datatables::of($userData)
                ->make(true);
        }  catch (\Exception $e) {
            Log::error($e);
        }
                       
    }
    
    /**
     * Method to view page for reset password
     * @return view
     */
    public function adminResetPassword($id)
    {
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->select(
                                'users.email','users.id','users.is_active'
                                )
                        ->where('users.id', $id)->first();
        return view('cms.recruiter.admin-reset-password',['userProfile'=>$user]);
    }
    
    /**
     * Method to send email for reset password
     * @return view
     */
    public function storeAdminResetPassword(Request $request)
    {
        try {
            if(isset($request->id)){
                $rules['email'] = "email|required";
                $this->validate($request, $rules);
                $reqData = $request->all();
                $userId = $reqData['id'];
                $email = $reqData['email'];
                User::where('id',$userId)->update(['password'=>'']);

                PasswordReset::where('user_id' , $userId)->where('email', $email)->delete();
                $token = \Illuminate\Support\Facades\Crypt::encrypt($email . time());
                $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userId, 'email' => $email));
                $passwordModel->fill(['token' => $token]);
                if($passwordModel->save()) {
                    $url = url('password/reset', ['token' => $token]);
                    Mail::to($email)->queue(new ResetPassword('Recruiter', $url));
                }

                $msg = trans('messages.admin_recruiter_password_success');
            }
            Session::flash('message',$msg);
            $user = UserGroup::where('user_id', $userId)->first();
            if($user->group_id == 2){
                return redirect('cms/recruiter/index');
            }else{
                return redirect('cms/jobseeker/index');
            }
        } catch(\Exception $e) {
            Log::error($e);
            Session::flash('message',$e->getMessage());
        }
        
    }
    
    /**
     * Method to view recruiter detail view
     * @return view
     */
    public function recruiterView($id) {
        try{
            $userProfile = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->leftjoin('recruiter_profiles', 'recruiter_profiles.user_id', '=', 'users.id')
                        ->select(
                                'recruiter_profiles.office_name',
                                'recruiter_profiles.office_desc',
                                'users.email','users.id',
                                'users.is_active'
                                )
                        ->where('users.id', $id)->first();
        
        return view('cms.recruiter.view',['userProfile'=>$userProfile]);
        }catch (\Exception $e) {
            Log::error($e);
        } 
    }

}
