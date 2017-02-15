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
        $affiliation = Affiliation::find($id);
        
        return view('cms.affiliation.update',['affiliation'=>$affiliation]);
    }

    /**
     * Store a new/update location.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function store(Request $request)
    {
        // Validate and store the location...
        $rules = array(
            'email' => array('required','unique:users,email'),
            'firstname' => array('required'),
            'lastname' => array('required'),
        );
        $this->validate($request, $rules);
        if(isset($request->id)){
            $rules['affiliation'] = "Required|Unique:affiliations,affiliation_name,".$request->id;
            $affiliation = Affiliation::find($request->id);  
            $msg = trans('messages.affiliation_updated');
        }
        else{
            $user =  array(
                'email' => $reqData['email'],
                'password' => '',
                'is_verified' => 1,
            );
            $userDetails = User::create($user);
            $userGroupModel = new UserGroup();
            $userGroupModel->group_id = 3;
            $userGroupModel->user_id = $userDetails->id;
            $userGroupModel->save();
            
            $userProfileModel = new UserProfile();
            $userProfileModel->user_id = $userDetails->id;
            $userProfileModel->first_name = $reqData['firstName'];
            $userProfileModel->last_name = $reqData['lastName'];
            $userProfileModel->save();
            
            $token = md5($reqData['email'] . time());
            $passwordModel = PasswordReset::firstOrNew(array('user_id' => $userDetails->id, 'email' => $user->email));
            $passwordModel->fill(['token' => $token]);
            $passwordModel->save();

            Mail::queue('email.resetPasswordToken', ['name' => $user->first_name, 'url' => url('password/reset', ['token' => $token]), 'email' => $reqData['email']], function($message) use ($reqData) {
                $message->to($reqData['email'], $reqData['firstName'])->subject('Set Password Email');
            });
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
                    $delete =url('cms/jobseeker/'.$userData->id.'/delete');
                    $action = '<a href="'.$edit.'"  class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>&nbsp;';
//                    $action .= '<a href="'.$delete.'" onclick="deleteRecord(this);return false;"  class="delete btn btn-xs btn-primary" onclick="return confirm(\'Are you sure you want to delete this location?\')"><i class="fa fa-remove"></i> Delete</a>';
                    return $action;
                       
                })
                ->make(true);
                       
    }
}
