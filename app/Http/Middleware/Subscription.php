<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        $subscribe = \App\Models\RecruiterProfile::where(['user_id' => Auth::user()->id])->first();
        if($subscribe && $subscribe['is_subscribed'] == 1){
            $result = $next($request);
        }else{
            $result = redirect('setting-subscription');
        }
        return $result;
    }
}
