<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Yajra\Datatables\Datatables;
use Session;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator;
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
     * Show the form to create a new location.
     *
     * @return Response 
     */
    public function create()
    {
        return view('cms.recruiter.create');
    }
    
    /**
     * List all locations.
     *
     * @param  array  $data
     * @return User
     */
    public function index()
    {  
        return view('cms.recruiter.index');
    }
    
    /**
     * Show the form to update an existing location.
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
     * Store a new/update location.
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
                $rules['email'] = "email|required";
                
                $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
                }
                $activationStatus  = isset($request->is_active) ? 1 : 0;

                User::where('id',$request->id)->update(['is_active' => $activationStatus]);
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

                $token = md5($reqData['email'] . time());
                $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userId, 'email' => $reqData['email']));
                $passwordModel->fill(['token' => $token]);
                $passwordModel->save();

                Mail::queue('email.resetPasswordToken', ['name' => "Recruiter", 'url' => url('password/reset', ['token' => $token]), 'email' => $reqData['email']], function($message) use ($reqData) {
                    $message->to($reqData['email'], "Recruiter")->subject('Set Password Email');
                });
                $msg = trans('messages.recruiter_added_success');
            }

            Session::flash('message',$msg);
        return redirect('cms/recruiter/index');
        } catch (\Exception $e) {
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
        User::where('id',$id)->update(['is_active'=>0]);

        Session::flash('message',trans('messages.recruiter_deleted'));
        return redirect('cms/recruiter/index');
        
    }
    
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
                ->removeColumn('id')
                ->addColumn('active', function ($userData) {
                	$active = ($userData->is_active == 1) ? 'Yes':'No';
                    return $active;
                })
                ->addColumn('action', function ($userData) {
                    $edit = url('cms/recruiter/'.$userData->id.'/edit');
                    $delete =url('cms/recruiter/'.$userData->id.'/delete');
                    $resetPassword = url('cms/recruiter/'.$userData->id.'/adminResetPassword');
                    $view = url('cms/recruiter/'.$userData->id.'/view');
                    $action = '<a href="'.$view.'"  class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> View</a>&nbsp;';
                    $action .= '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    //$action .= '<a href="'.$delete.'" class="delete btn btn-xs btn-primary" onclick="return confirm(\'Are you sure you want to delete this recruiter?\');"><i class="fa fa-remove"></i> Delete</a>&nbsp;';
                    $action .= '<a href="'.$resetPassword.'"  class="btn btn-xs btn-primary">Reset Password</a>&nbsp;';
                    return $action;
                })
                ->make(true);
        }  catch (\Exception $e) {
            Log::error($e);
        }
                       
    }
    
    public function adminResetPassword($id)
    {
        $user = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->select(
                                'users.email','users.id','users.is_active'
                                )
                        ->where('users.id', $id)->first();
        return view('cms.recruiter.adminResetPassword',['userProfile'=>$user]);
    }
    
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
                $token = md5($email . time());
                $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userId, 'email' => $email));
                $passwordModel->fill(['token' => $token]);
                if($passwordModel->save()) {
                    Mail::queue('email.resetPasswordToken', ['name' => "Recruiter", 'url' => url('password/reset', ['token' => $token]), 'email' => $reqData['email']], function($message) use ($email) {
                        $message->to($email, "Recruiter")->subject('Set Password Email');
                    });
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
