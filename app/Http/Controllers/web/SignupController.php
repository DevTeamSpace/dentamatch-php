<?php

namespace App\Http\Controllers\web;

use App\Enums\SignupSource;
use App\Mail\AdminVerifyJobseeker;
use App\Mail\UserActivation;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\RecruiterProfile;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Auth;
use Session;
use Mail;
use DB;
use Log;
use App\Models\UserProfile;
use App\Models\JobTitles;
use App\Models\PreferredJobLocation;
use App\Models\JobSeekerSkills;
use App\Models\JobSeekerTempAvailability;
use App\Models\PasswordReset;
use Hash;

class SignupController extends Controller
{

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
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['postJobseekerSignUp', 'getJobseekerSignUp', 'postSignUp', 'postLogin', 'getLogin', 'logout', 'resetPassword', 'getTermsAndCondition', 'dashboard', 'getVerificationCode']]);
    }

    /**
     * Method to view login page
     * @return view
     */
    public function getLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect('users/dashboard');
        }
        return view('web.login');
    }

    /**
     * Method to check user login
     * @return view
     */
    protected function postLogin(Request $request)
    {
        $validation_rules = ['email' => 'required|email', 'password' => 'required'];
        $validator = Validator::make($request->all(), $validation_rules);
        if ($validator->fails()) {
            Session::flash('message', "Validation Failure");
        }
        $users = User::where('email', $request->email)->first();
        $credentials = ['email' => $request->email, 'password' => $request->password, 'is_verified' => 1, 'is_active' => 1];
        $message = trans("messages.invalid_cred_or_not_active");
        $redirect = 'login';
        if (Auth::validate($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user->userGroup->group_id == UserGroup::RECRUITER && Auth::attempt($credentials)) {
                $redirect = 'terms-conditions';
                $term = RecruiterProfile::where('user_id', Auth::user()->id)->first();
                $request->session()->put('userData', ['basic' => $user->toArray(), 'profile' => $term->toArray()]);
                if (!empty($term) && isset($term) && $term->accept_term == 1) {
                    if (empty($term->office_name)) {
                        $redirect = 'home';
                    } else {
                        $redirect = 'users/dashboard';
                    }

                }
            }
        } else if (!empty($users) && $users['is_active'] == 0) {
            $message = trans("messages.deactivated_admin");
        } else if (empty($users)) {
            $message = trans("messages.user_not_registered");
        } else {
            $message = trans("messages.invalid_credentials");
        }
        Session::flash('message', $message);
        return redirect($redirect);
    }

    /**
     * Log the user out of the application.
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Method to get Terms And Condition
     * @return view
     */
    public function getTermsAndCondition(Request $request)
    {
        $request->session()->put('tutorial', 1);
        return view('web.terms-conditions');
    }

    /**
     * Method to home page
     * @return view
     */
    public function dashboard()
    {
        $officeType = \App\Models\OfficeType::where('is_active', 1)->orderBy('officetype_name', 'ASC')->get();
        return view('web.dashboard')->with('officeType', $officeType);
    }

    /**
     * Method to get user registered
     * @return view
     */
    public function postSignUp(Request $request)
    {
        $redirect = 'login';
        try {
            DB::beginTransaction();
            $validation_rules = ['email' => 'required|email', 'password' => 'required'];
            $validator = Validator::make($request->all(), $validation_rules);
            if ($validator->fails()) {
                Session::flash('message', trans("messages.validation_failure"));
            }

            $reqData = $request->all();

            $userExists = User::with('userGroup')->where('email', $reqData['email'])->first();
            if ($userExists) {
                if (isset($userExists->userGroup) && !empty($userExists->userGroup)) {
                    if ($userExists->userGroup->group_id == UserGroup::JOBSEEKER) {
                        Session::flash('message', trans("messages.already_register_as_seeker"));
                    } else {
                        Session::flash('message', trans("messages.email_already_regisered"));
                    }
                }
            } else if ($reqData['password'] !== $reqData['confirmPassword']) {
                Session::flash('message', trans("messages.password_not_match_confirm"));
            } else {
                $uniqueCode = uniqid();
                $user = [
                    'email'             => $reqData['email'],
                    'password'          => bcrypt($reqData['password']),
                    'verification_code' => $uniqueCode,
                ];
                $user_details = User::create($user);
                RecruiterProfile::create(['user_id' => $user_details->id]);
                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = UserGroup::RECRUITER;
                $userGroupModel->user_id = $user_details->id;
                $userGroupModel->save();

                $url = url("/verification-code/$uniqueCode");
                Mail::to($reqData['email'])->queue(new UserActivation(null, $url, 'auth.emails.user-activation'));

                DB::commit();
                Session::flash('success', trans("messages.successfully_register"));
            }
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            Session::flash('message', $e->getMessage());
        }
        return redirect()->intended($redirect);
    }

    /**
     * Method to get seeker  registered
     * @return view
     */
    public function postJobseekerSignUp(Request $request)
    {

        try {
            $this->validate($request, [
                'firstName'              => 'required',
                'lastName'               => 'required',
                'email'                  => 'required',
                'preferredJobLocationId' => 'required',
                'jobTitleId'             => 'required',
                'aboutMe'                => 'required',
            ]);
            $mappedSkillsArray = [];
            $validateKeys = [];
            $isComplete = 1;
            $jobTitleModel = JobTitles::where('id', $request->jobTitleId)->first();
            if ($jobTitleModel) {
                if ($jobTitleModel->mapped_skills_id == "") {
                    $mappedSkillsArray = [];
                } else {
                    $mappedSkills = $jobTitleModel->mapped_skills_id;
                    $mappedSkillsArray = explode(",", $mappedSkills);
                }
                if ($jobTitleModel->is_license_required) {
                    $isComplete = 0;
                    $validateKeys['license'] = 'required';
                    $validateKeys['state'] = 'required';
                }
            }

            if (!empty($validateKeys)) {
                $this->validate($request, $validateKeys);
            }

            $reqData = $request->all();
            DB::beginTransaction();

            $userExists = User::with('userGroup')->where('email', $reqData['email'])->first();
            if ($userExists) {
                if (isset($userExists->userGroup) && !empty($userExists->userGroup)) {
                    if ($userExists->userGroup->group_id == UserGroup::RECRUITER) {
                        Session::flash('message', trans("messages.already_register_as_recruiter"));
                    } else {
                        Session::flash('message', trans("messages.user_exist_same_email"));
                    }
                }
            } else {
                $uniqueCode = uniqid();
                $user = [
                    'email'             => $reqData['email'],
                    'password'          => isset($reqData['password']) ? Hash::make($reqData['password']) : null,
                    'verification_code' => $uniqueCode,
                ];
                $userDetails = User::create($user);
                $userGroupModel = new UserGroup();
                $userGroupModel->group_id = UserGroup::JOBSEEKER;
                $userGroupModel->user_id = $userDetails->id;
                $userGroupModel->save();

                $userProfileModel = new UserProfile();

                $userProfileModel->user_id = $userDetails->id;
                $userProfileModel->first_name = $reqData['firstName'];
                $userProfileModel->last_name = $reqData['lastName'];
                $userProfileModel->about_me = $reqData['aboutMe'];
                $userProfileModel->license_number = isset($reqData['license']) ? $reqData['license'] : "";
                $userProfileModel->state = isset($reqData['state']) ? $reqData['state'] : "";
                $userProfileModel->job_titile_id = $reqData['jobTitleId'];
                $userProfileModel->preferred_job_location_id = $reqData['preferredJobLocationId'];
                $userProfileModel->is_fulltime = config('constants.AutoAvailabilityFlag');
                $userProfileModel->is_parttime_monday = config('constants.AutoAvailabilityFlag');
                $userProfileModel->is_parttime_tuesday = config('constants.AutoAvailabilityFlag');
                $userProfileModel->is_parttime_wednesday = config('constants.AutoAvailabilityFlag');
                $userProfileModel->is_parttime_thursday = config('constants.AutoAvailabilityFlag');
                $userProfileModel->is_parttime_friday = config('constants.AutoAvailabilityFlag');
                $userProfileModel->is_parttime_saturday = config('constants.NonAvailabilityFlag');
                $userProfileModel->is_parttime_sunday = config('constants.NonAvailabilityFlag');
                $userProfileModel->profile_pic = config('constants.defaultProfileImage');
                $userProfileModel->is_completed = $isComplete;
                $userProfileModel->is_job_seeker_verified = $isComplete;
                $userProfileModel->signup_source = SignupSource::WEB;

                $userProfileModel->save();

                if (!empty($mappedSkillsArray)) {
                    JobSeekerSkills::addJobSeekerSkills($userDetails->id, $mappedSkillsArray);
                }

                $current = strtotime(date('Y-m-d'));
                $last = strtotime(date('Y-m-d') . " +60 days");
                JobSeekerTempAvailability::addTempDateAvailability($userDetails->id, $current, $last);

                $url = url("/verification-code/$uniqueCode");
                $name = $reqData['firstName'] . ' ' . $reqData['lastName'];
                $email = $reqData['email'];

                Mail::to($email)->queue(new UserActivation($name, $url));

                if (!empty($reqData['license']) && !empty($reqData['state'])) {
                    $adminEmail = env('ADMIN_EMAIL');
                    Mail::to($adminEmail)->queue(new AdminVerifyJobseeker($name, $email));
                }

                Session::flash('message', trans("messages.user_registration_successful"));
            }
            DB::commit();

            Session::flash('success', trans("messages.successfully_register"));


        } catch (ValidationException $e) {
            $messages = json_decode($e->getResponse()->content(), true);
            Log::info($messages);
            return redirect('jobseeker/signup');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            Session::flash('message', $e->getMessage());
        }
        return redirect('jobseeker/signup');
    }

    /**
     * Method to get seeker signup page
     * @return view
     */
    public function getJobseekerSignUp()
    {
        $jobTitleData = JobTitles::getAll(JobTitles::ACTIVE);
        $preferredLocationId = PreferredJobLocation::getAllPreferrefJobLocation();
        return view('web.jobseekerSignup', ['jobTitleData' => $jobTitleData, 'preferredLocationId' => $preferredLocationId]);
    }

    /**
     * Method to check verification code
     * @return view
     */
    public function getVerificationCode($code)
    {
        $user = DB::table('users')
            ->join('user_groups', 'users.id', '=', 'user_groups.user_id')
            ->select('user_groups.group_id', 'users.id', 'email')
            ->where('users.verification_code', $code)
            ->first();
        Auth::logout();
        Session::flush();
        $redirect = 'login';
        try {
            if (isset($user) && !empty($user)) {
                User::where('verification_code', $code)->update(['is_verified' => 1, 'is_active' => 1]);
                Session::flash('success', trans("messages.verified_user"));
                if ($user->group_id == UserGroup::JOBSEEKER) {
                    $userProfileModel = UserProfile::getUserProfile($user->id);
                    $msg = "Hi " . $userProfileModel['first_name'] . " , <br />Your account is ready for you! Please login with the DentaMatch app.";
                    Session::flash('message', $msg);
                    $redirect = 'success-active';
                    if ($userProfileModel['signup_source'] == SignupSource::WEB) {
                        $token = \Illuminate\Support\Facades\Crypt::encrypt($user->email . time());
                        $passwordModel = PasswordReset::firstOrNew(['user_id' => $user->id, 'email' => $user->email]);
                        $passwordModel->fill(['token' => $token]);
                        $passwordModel->save();
                        $redirect = 'password/reset/' . $token;
                    }
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

    /**
     * Method to view tutorials
     * @return view
     */
    public function getTutorial(Request $request)
    {
        try {
            $tutorial = $request->session()->pull('tutorial', 0);
            if ($tutorial == 1) {
                $officeType = \App\Models\OfficeType::where('is_active', 1)->get();
                RecruiterProfile::where('user_id', Auth::user()->id)->update(['accept_term' => 1]);
                return view('web.dashboard')->with('modal', 1)->with('officeType', $officeType);
            } else {
                return redirect('home');
            }
        } catch (\Exception $e) {
            Log::error($e);
            return redirect('terms-conditions');
        }
    }

}
