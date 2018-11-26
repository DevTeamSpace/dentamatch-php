<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
        $user = User::where('id', Auth::user()->id)->first();
        
        if(isset($user) && $user->is_active!=1){
            Session::flash('message', trans("messages.deactivated_admin"));
            return redirect("logout");
        }elseif(isset($user) && $user->userGroup->group_id==\App\Models\UserGroup::ADMIN){
            return redirect("cms");
        }else{
            return $next($request);
        }
    }
}
