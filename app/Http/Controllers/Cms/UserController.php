<?php
namespace App\Http\Controllers\Cms;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Session;

class UserController extends Controller {
    
    public function __construct() {
        $this->middleware('cms')->except([]);
    }
    
    public function index(){
        dd('after login');
    }
    
    /**
     * Change password.
     *
     * @return User
     */
    public function changePassword()
    {  
        return view('cms.users.changePassword');
    }
    
    /**
     * Update user password.
     *
     * @param  Request  $request
     * @return return to lisitng page
     */
    public function updatePassword(Request $request)
    {
        $id = Auth::id();
        $credentials = [
            'id' => $id,
            'password' => $request->get('old_password'),
        ];
            
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }elseif(Auth::validate($credentials)) {
            $user = User::findOrFail($id);
            $user->password = bcrypt($request->password);
            $user->save();
            Session::flash('message',trans('messages.password_updated'));
            return redirect('/cms');
        }else{
            $validator->after(function($validator) {
                $validator->errors()->add('incorrect', trans('messages.incorrect_pass'));
            });
            if ($validator->fails()) {
                return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
            }
        }
    }
    
    
}