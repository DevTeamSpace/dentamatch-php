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
        $this->middleware('auth', ['except' => ['postSignUp', 'postLogin', 'getLogin', 'logout', 'resetPassword', 'getTermsAndCondition', 'dashboard', 'getVerificationCode']]);
    }

    public function getLogin(Request $request) {
        return view('web.login');
    }

    protected function postLogin(\Illuminate\Http\Request $request) {

        $response = array('success' => 0, 'message' => '', 'result' => []);
        $validation_rules = array('email' => 'required|email', 'password' => 'required');
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            Session::flash('message', "Validation Failure");
        }
        $credentials = ['email' => $request->email, 'password' => $request->password, 'is_active' => 1];
        $message = "Invalid username or password or not active yet.";
        $redirect = 'login';
        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user->userGroup->group_id == 2) {
                if (Auth::attempt($credentials, $request->remember)) {
                    $message = "Successfully Login";
                    $redirect = 'terms-conditions';
                    if ($user->remember_token) {
                        $redirect = 'home';
                    }
                }
            }
        }
        Session::flash('message', $message);
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

    public function getTermsAndCondition() {
        return view('web.terms-conditions');
    }

    public function dashboard() {
        return view('web.dashboard');
    }

    public function postSignUp(Request $request) {
        $redirect = 'login';
        try {

            $validation_rules = array('email' => 'required|email', 'password' => 'required');
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                Session::flash('message', "Validation Failure");
            }

            $reqData = $request->all();

            $userExists = User::where('email', $reqData['email'])->first();
            if ($userExists) {
                Session::flash('message', "Email already registered");
            } else if ($reqData['password'] !== $reqData['confirmPassword']) {
                Session::flash('message', "Password and confirm password do not match");
            } else {
                $uniqueCode = uniqid();
                $user = array(
                    'email' => $reqData['email'],
                    'password' => bcrypt($reqData['password']),
                    'verification_code' => $uniqueCode,
                );
                $user_details = User::create($user);

                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = 2;
                $userGroupModel->user_id = $user_details->id;
                $userGroupModel->save();

                Mail::send('auth.emails.userActivation', ['url' => url("/verification-code/$uniqueCode")], function ($message) use ($reqData) {
                    $message->to($reqData['email'])
                            ->subject('Confirmation Link for new user.');
                });

                Session::flash('success', "User registered successfully. A Confirmation link is send on your mail.");
            }
        } catch (\Exception $e) {
            Session::flash('message', $e->getMessage());
        }
        return redirect($redirect);
    }

    public function getVerificationCode($code) {
        $user = User::where('verification_code', $code)->first();
        try {
            if (isset($user) && !empty($user)) {
                User::where('verification_code', $code)->update(['is_active' => 1]);
                Session::flash('success', "User verified successfully. You can login.");
            } else {
                Session::flash('message', "Problem in verification process. Please contact admin.");
            }
        } catch (\Exception $e) {
            Session::flash('message', "Problem in verification process. Please contact admin.");
        }
        return redirect('login');
    }

}
