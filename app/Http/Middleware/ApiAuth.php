<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use DB;
use Lang;
use App\Http\Controllers\Api\ValidateJson;
use Validator;
use App\Models\Device;
use App\Helpers\apiResponse;


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
            $user = Device::getUserByDeviceToken($request->header('accessToken'));
            if (!$user) {
                $response = apiResponse::customJsonResponse(1, 204, "Token is invalid");
            }
            
        } catch (Exception $ex) {
            return apiResponse::responseError("Request validation failed.");
        }
        return $next($request);
    }

}
