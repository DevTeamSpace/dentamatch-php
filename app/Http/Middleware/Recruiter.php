<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use Session;

class Recruiter {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $user = User::where('id', Auth::user()->id)->first();
        
        if(isset($user) && $user->is_active!=1){
            Session::flash('message', trans("messages.deactivated_admin"));
            return redirect("logout");
        }elseif (!empty($user) && isset($user)) {
            if ($user->userGroup->group_id == \App\Models\UserGroup::ADMIN) {
                return redirect('cms');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }

}
