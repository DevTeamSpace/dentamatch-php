<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Device;
use App\Helpers\ApiResponse;


class ApiAuth {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public $attributes;

    public function __construct() {

    }
    
    
    public function handle($request, Closure $next) {
        try {
            $user = Device::select('user_id')->where('user_token',$request->header('accessToken'))->first();
            if (!$user) {
                return ApiResponse::customJsonResponse(1, 204, "Token is invalid");
            }else{
                $request->merge(['userServerData' => $user]);
            }
            
        } catch (\Exception $ex) {
            return ApiResponse::responseError("Request validation failed.");
        }
        return $next($request);
    }

}