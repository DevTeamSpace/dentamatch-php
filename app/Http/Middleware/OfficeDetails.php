<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OfficeDetails {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $office = \App\Models\RecruiterOffice::where('user_id', Auth::user()->id)->first();
        if (!empty($office) && isset($office)) {
            return redirect('subscription-detail');
        }
        return $next($request);
    }

}
