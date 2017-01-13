<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        $term = \App\Models\UserGroup::where('user_id', Auth::user()->id)->first();
        if (!empty($term) && isset($term)) {
            if ($term->group_id != 2) {
                return redirect('login');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }

}
