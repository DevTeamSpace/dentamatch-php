<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Device;
use App\Helpers\ApiResponse;

class ApiAuth
{
    public $attributes;

    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // todo user id can be null? or 0?
        $device = Device::select('user_id')->where('user_token', $request->header('accessToken'))->first();
        if (!$device) {
            return ApiResponse::customJsonResponse(1, 204, "Token is invalid");
        } else {
            $request->merge(['apiUserId' => $device->user_id]);
        }

        return $next($request);
    }
}