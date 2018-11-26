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
        $term = Auth::user()->userGroup();
        if (!empty($term) && isset($term)) {
            if ($term->group_id == \App\Models\UserGroup::ADMIN) {
                return redirect('cms');
            }
            return $next($request);
        } else {
            return $next($request);
        }
    }

}
