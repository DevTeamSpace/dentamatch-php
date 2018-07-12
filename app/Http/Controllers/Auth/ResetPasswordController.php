<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Session;

class ResetPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null) {
        $email = DB::table('password_resets')->where('token', $token)->pluck('email');
        return view('auth.passwords.reset')->with(
                        ['token' => $token, 'email' => $email]
        );
    }

    public function reset(Request $request) {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
                $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        if ($response == 'passwords.user') {
            $response = 'Token has been expired.';
        }
        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET ? $this->sendResetResponse($request, $response) : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules() {
        return [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function credentials(Request $request) {
        return $request->only(
                        'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function sendResetResponse($request, $response) {
        $users = DB::table('users')
                ->join('user_groups', 'users.id', '=', 'user_groups.user_id')
                ->select('user_groups.group_id')
                ->where('users.email', $request['email'])
                ->first();
        if ($users->group_id == 3) {
            Auth::logout();
            return redirect('/success-register');
        }else if($users->group_id == 1){
            Auth::logout();
            return redirect('/cms/login');
        }
        else {
            $message = 'Password Reset successfully';
            Session::flash('success', $message);
             Auth::logout();
            return redirect('/');
        }
    }
}
