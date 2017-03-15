<?php

namespace App\Http\Controllers\web;

use App\Models\User;
use App\Models\UserGroup;
use App\Models\RecruiterProfile;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Session;
use Mail;
use DB;
use Log;

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
        if(Auth::check()){
            return redirect('home');
        }
        return view('web.login');
    }

    protected function postLogin(\Illuminate\Http\Request $request) {

        $validation_rules = array('email' => 'required|email', 'password' => 'required');
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            Session::flash('message', "Validation Failure");
        }
        $credentials = ['email' => $request->email, 'password' => $request->password, 'is_verified' => 1, 'is_active' => 1];
        $message = trans("messages.invalid_cred_or_not_active");
        $redirect = 'login';
        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user->userGroup->group_id == 2 && Auth::attempt($credentials)) {
                $redirect = 'terms-conditions';
                $term = RecruiterProfile::where('user_id', Auth::user()->id)->first();
                $request->session()->put('userData', ['basic'=>$user->toArray(),'profile'=>$term->toArray()]);
                if (!empty($term) && isset($term) && $term->accept_term==1) {
                    $redirect = 'home';
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

    public function getTermsAndCondition(Request $request) {
        $request->session()->set('tutorial', 1);
        return view('web.terms-conditions');
    }

    public function dashboard() {
        $officeType = \App\Models\OfficeType::orderBy('officetype_name', 'ASC')->get();
        return view('web.dashboard')->with('officeType', $officeType);
    }

    public function postSignUp(Request $request) {
        $redirect = 'login';
        try {
            DB::beginTransaction();
            $validation_rules = array('email' => 'required|email', 'password' => 'required');
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                Session::flash('message', trans("messages.validation_failure"));
            }

            $reqData = $request->all();

            $userExists = User::with('userGroup')->where('email', $reqData['email'])->first();
            if ($userExists) {
                if (isset($userExists->userGroup) && !empty($userExists->userGroup)) {
                    if ($userExists->userGroup->group_id == 3) {
                        Session::flash('message', trans("messages.already_register_as_seeker"));
                    } else {
                        Session::flash('message', trans("messages.email_already_regisered"));
                    }
                }
            } else if ($reqData['password'] !== $reqData['confirmPassword']) {
                Session::flash('message', trans("messages.password_not_match_confirm"));
            } else {
                $uniqueCode = uniqid();
                $user = array(
                    'email' => $reqData['email'],
                    'password' => bcrypt($reqData['password']),
                    'verification_code' => $uniqueCode,
                );
                $user_details = User::create($user);
                RecruiterProfile::create(['user_id' => $user_details->id]);
                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = 2;
                $userGroupModel->user_id = $user_details->id;
                $userGroupModel->save();

                Mail::send('auth.emails.userActivation', ['url' => url("/verification-code/$uniqueCode")], function ($message) use ($reqData) {
                    $message->to($reqData['email'])
                            ->subject(trans("messages.confirmation_link"));
                });
                DB::commit();
                Session::flash('success', trans("messages.successfully_register"));
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            Session::flash('message', $e->getMessage());
        }
        return redirect($redirect);
    }

    public function getVerificationCode($code) {
        $user = DB::table('users')
                ->join('user_groups', 'users.id', '=', 'user_groups.user_id')
                ->select('user_groups.group_id')
                ->where('users.verification_code', $code)
                ->first();
        $redirect = 'login';
        try {
            if (isset($user) && !empty($user)) {
                User::where('verification_code', $code)->update(['is_verified' => 1, 'is_active' => 1]);
                Session::flash('success', trans("messages.verified_user"));
                if ($user->group_id == 3) {
                    $redirect = 'success-active';
                }
            } else {
                Session::flash('message', trans("messages.verified_problem"));
            }
        } catch (\Exception $e) {
            Log::error($e);
            Session::flash('message', trans("messages.verified_problem"));
        }
        return redirect($redirect);
    }

    public function getTutorial(Request $request) {
        try {
            $tutorial = $request->session()->pull('tutorial', 0);
            if($tutorial == 1){
                $officeType = \App\Models\OfficeType::all();
                RecruiterProfile::where('user_id',Auth::user()->id)->update(['accept_term' => 1]);
                return view('web.dashboard')->with('modal', 1)->with('officeType', $officeType);
            }else{
                return redirect('home');
            }
        } catch (\Exception $e) {
            Log::error($e);
            return redirect('terms-conditions');
        }
    }

}
