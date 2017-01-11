<?php
namespace App\Http\Controllers\web;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\Group;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Config;
use Session;
use Redirect;
use Mail;

class SignupController extends Controller {

use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectAfterLogout = '/login';
    /**
     * Create a new authentication controller instance.
     * @return void
     */
    public function __construct() {
        $this->middleware('auth', ['except' => ['postSignUp','postLogin','getLogin','logout', 'resetPassword', 'getTermsAndCondition', 'dashboard']]);
    }
    
    public function getLogin(Request $request)
    {
        return view('web.login');
    }
    
    protected function postLogin(\Illuminate\Http\Request $request) {
        
        $response = array('success' => 0, 'message' => '', 'result' => []);
        $validation_rules = array('email' => 'required|email', 'password' => 'required');
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            Session::flash('message',"Validation Failure");
        }
        $credentials = ['email' => $request->email, 'password' => $request->password];
        $message ="Invalid username or password";
        $redirect = 'login';
        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user->userGroup->group_id == 2) {
                if (Auth::attempt($credentials, $request->remember)) {
                    $message = "Successfully Login";
                    $redirect = 'terms-conditions';
                    if($user->remember_token)
                    {
                        $redirect = 'dashboard';
                    }
                }
            }
        }
        Session::flash('message',$message);
        return redirect($redirect);
    }
    /**
     * Log the user out of the application.
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        Auth::logout();
        Session::flush();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
    
    public function getTermsAndCondition()
    {
        return view('web.terms-conditions');
    }
    
    public function dashboard()
    {
        return view('web.dashboard');
    }
    
    public function postSignUp(Request $request)
    {
        $redirect = 'login';
        try {
            
            $validation_rules = array('email' => 'required|email', 'password' => 'required');
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                Session::flash('message',"Validation Failure");
            }
            
            $reqData = $request->all();
            
            $userExists = User::where('email', $reqData['email'])->first();
            if($userExists){
                Session::flash('message',"Email already registered");
            } 
            else if($reqData['password']!==$reqData['confirmPassword'])
            {
                Session::flash('message',"Password and confirm password do not match");
            }
            else 
            {
                $user =  array(
                    'email' => $reqData['email'],
                    'password' => bcrypt($reqData['password']),
                );
                $user_details = User::create($user);

                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = 2;
                $userGroupModel->user_id = $user_details->id;
                $userGroupModel->save();

                Session::flash('message',"User registered successfully"); 
            }
            
        } catch (\Exception $e) {
            Session::flash('message',$e->getMessage());
        }
        return redirect($redirect);
    }
    
}
