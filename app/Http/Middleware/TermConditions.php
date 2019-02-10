<?php

namespace App\Http\Middleware;

use Closure;

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
        $user = $request->session()->get('userData');
        
        if (!empty($user) && isset($user) && $user['profile']['accept_term']==1) {
            return redirect('home');
        } else {
            return $next($request);
        }
    }

}
