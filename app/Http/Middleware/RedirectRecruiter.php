<?php

namespace App\Http\Middleware;

use App\Models\UserGroup;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectRecruiter {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $user = Auth::user();
        if ($user && $user->userGroup->group_id == UserGroup::RECRUITER) {
            return redirect('/edit-profile');
        }
        return $next($request);
    }

}
