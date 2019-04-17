<?php

namespace App\Http\Middleware;

use App\Models\RecruiterProfile;
use Closure;

class Subscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $recruiter = RecruiterProfile::current();
        if($recruiter && ($recruiter->is_subscribed || $recruiter->subscribed())){
            $result = $next($request);
        }else{
            $result = redirect('setting-subscription');
        }
        return $result;
    }
}
