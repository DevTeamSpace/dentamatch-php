<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/cms';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function getLogin()
    {
        return $this->showLoginForm();
    }
    
    protected function login(\Illuminate\Http\Request $request) {
        
        $this->validate($request, [
            'email' => 'required', 'password' => 'required',
        ]);
        
        $credentials = ['email' => $request->email, 'is_active'=>1];
        $user = User::where($credentials)->first();
        
        $msg = trans('messages.not_admin_email');
        if(isset($user) && ($user->userGroup->group_id==1)){
            $credentials['password'] = $request->password;
            if (Auth::attempt($credentials,$request->remember)) {
                // Authentication passed...
                return redirect()->intended('/cms');
            }else{
                $msg = trans('messages.credentials_not_matched');
            }
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => $msg,
            ]);
    }
    
    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(){
        //$user = Auth::user();
        //Auth::guard($this->getGuard())->logout();
        Auth::guard()->logout();
        
        return redirect(property_exists($this, 'redirectTo') ? $this->redirectTo : '/');
    }
}
