<?php

namespace App\Http\Middleware;

use App\Utils\ActionLogUtils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiActionLog
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->merge(['startLogTime' => microtime(true)]);
        return $next($request);
    }

    /**
     * @param Request$request
     * @param Response $response
     */
    public function terminate($request, $response)
    {
        ActionLogUtils::logSimpleApiRequest($request, $response);
    }

}
