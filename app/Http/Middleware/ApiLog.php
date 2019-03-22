<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiLog
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
        $request->merge(['endLogTime' => microtime(true)]);
        $this->log($request,$response);
    }

    /**
     * @param  Request  $request
     * @param  Response $response
     * @return mixed
     */
    protected function log($request, $response)
    {
        $entry = new RequestLog();
        $entry->user_id = $request->apiUserId;
        $entry->path = $request->getMethod() . " " . $request->fullUrl();
        $entry->ip = $request->getClientIp();
        $entry->duration = number_format($request->endLogTime - $request->startLogTime, 5);
        $entry->request = json_encode($request->all());
        $entry->response = $response->getContent();
        $entry->save();
    }
}
