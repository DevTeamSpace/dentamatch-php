<?php

namespace App\Http\Middleware;

use App\Models\RecruiterOffice;
use Closure;
use Illuminate\Support\Facades\Auth;

class OfficeDetails {

    /**
     * If at least one office added - check the subscription status
     * Otherwise show /home url with form to create an office
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null) {
        $office = RecruiterOffice::where('user_id', Auth::user()->id)->first();
        if (!empty($office) && isset($office)) {
            return redirect('subscription-detail');
        }
        return $next($request);
    }

}
