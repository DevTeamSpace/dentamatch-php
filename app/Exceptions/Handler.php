<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)){
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request $request
     * @param  \Exception $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->route() && $request->route()->getPrefix() === 'api/v1') {

            if ($exception instanceof ValidationException) {
                return ApiResponse::errorResponse(trans("messages.validation_failure"), ["data" => $exception->errors()]);
            }

            return ApiResponse::errorResponse(trans("messages.something_wrong"), ["data" => $exception->getMessage()]);
        }

        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {
                case 404:
                case 500:
                    return redirect()->guest('/');
                default:
                    if ($exception instanceof ModelNotFoundException) {
                        $message = $exception->getMessage();
                    } else if ($exception instanceof MethodNotAllowedHttpException) {
                        $message = 'The page you are looking for is not available';
                    }
                    return response()->view('errors.404', ['message' => $message], 404);
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  Request $request
     * @param  AuthenticationException $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
