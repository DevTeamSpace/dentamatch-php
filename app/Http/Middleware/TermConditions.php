<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TermConditions {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $term = \App\Models\RecruiterProfile::where('user_id', Auth::user()->id)->first();
        if (!empty($term) && isset($term)) {
            return redirect('home');
        } else {
            return $next($request);
        }
    }

}
