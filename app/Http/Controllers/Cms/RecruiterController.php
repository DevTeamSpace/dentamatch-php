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
    protected function index()
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
                                'users.email','users.id','users.is_active',
                                'users.is_verified'
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
        if(isset($request->id)){
            $rules['email'] = "email|required";
            $this->validate($request, $rules);
            $activationStatus  = isset($request->is_active) ? 1 : 0;
            
            User::where('id',$request->id)->update(['is_active' => $activationStatus]);
            $msg = trans('messages.recruiter_updated_success');
        }
        else
        {
            $rules['email'] = "email|required|unique:users";
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
            $userGroupModel->group_id = 2;
            $userGroupModel->user_id = $userId;
            $userGroupModel->save();
            
            $token = md5($reqData['email'] . time());
            $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userId, 'email' => $reqData['email']));
            $passwordModel->fill(['token' => $token]);
            $passwordModel->save();

            Mail::queue('email.resetPasswordToken', ['name' => "Dear Recruiter", 'url' => url('password/reset', ['token' => $token]), 'email' => $reqData['email']], function($message) use ($reqData) {
                $message->to($reqData['email'], "Dear Recruiter")->subject('Set Password Email');
            });
            $msg = trans('messages.recruiter_added_success');
        }
        
        Session::flash('message',$msg);
        return redirect('cms/recruiter/index');
    }
    
    /**
     * Soft delete a location.
     *
     * @param  Location  $id
     * @return return to lisitng page
     */
    public function delete($id){
        User::findOrFail($id)->update(['is_active',0]);
        Session::flash('message',trans('messages.recruiter_deleted'));
        
    }
    
    public function recruiterList(){
        $userData = User::join('user_groups', 'user_groups.user_id', '=', 'users.id')
                        ->select(
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
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
                    return $action;
                })
                ->make(true);
                       
    }

}
