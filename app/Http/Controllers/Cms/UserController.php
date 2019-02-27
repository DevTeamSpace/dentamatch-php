<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('cms');
    }

    /**
     * Change password.
     *
     * @return Response
     */
    public function changePassword()
    {
        return view('cms.users.change-password');
    }

    /**
     * Update user password.
     *
     * @param  Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updatePassword(Request $request)
    {
        $id = Auth::id();
        $credentials = [
            'id'       => $id,
            'password' => $request->get('old_password'),
        ];

        $rules = [
            'old_password'          => ['required', 'min:6', function ($attribute, $value, $fail) use ($credentials) {
                if (!Auth::validate($credentials)) {
                    $fail(trans('messages.incorrect_pass'));
                }
            }],
            'password'              => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => 'required|min:6'
        ];

        $this->validate($request, $rules);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();
        Session::flash('message', trans('messages.password_updated'));
        return redirect('/cms');

    }
}