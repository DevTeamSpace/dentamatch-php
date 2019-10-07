<?php

namespace App\Http\Middleware;

use App\Models\UserGroup;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;
class Cms
{
    protected $session;
    protected $timeout = 90000;

    public function __construct(Store $session){
        $this->session = $session;
    }
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
                return redirect()->guest('cms/login');
            }
        }
        return $this->handleSessionTimeout($request, $next);
    }

    public function handleSessionTimeout($request, $next){
        $user = Auth::user();

        if(isset($user) && $user->userGroup->group_id==UserGroup::ADMIN){
            $isLoggedIn = $request->path() != 'logout';
            if(! session('lastActivityTime')) {
                $this->session->put('lastActivityTime', time());
            }
            else if(time() - $this->session->get('lastActivityTime') > $this->timeout){
                $this->session->forget('lastActivityTime');
                $cookie = cookie('intend', $isLoggedIn ? url()->current() : 'cms');
                $email = $request->user()->email;
                auth()->logout();
                $msg = 'You had no activity in last '.$this->timeout/60 .' minutes ago.';
                return redirect("cms/login")->withInput(array('email'=>$email))
                        ->withErrors(['email'=>$msg])
                        ->withCookie($cookie);
            }
            $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
            return $next($request);
        }else{
            return redirect("/")->withMyerror("You are not authorized for this action");
        }

    }
}
