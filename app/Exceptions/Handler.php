<?php

namespace App\Exceptions;

use App\Firebase\PopoMapper;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        Log::error('[' . $exception->getCode() . '] "' . $exception->getMessage() . '" on line ' . (@$exception->getTrace()[0]['line'] ?? $exception->getLine()) . ' of file ' . (@$exception->getTrace()[0]['file'] ?? $exception->getFile()));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->wantsJson())
        {
            $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
            switch (get_class($exception))
            {
                case ModelNotFoundException::class :
                    return response()->json(PopoMapper::jsonResponse(404, 'Resource Not Found'), 404);
                default :
                    return response()->json(PopoMapper::jsonResponse($statusCode, $exception->getMessage()), $statusCode);
            }
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->wantsJson())
        {
            return response()->json(PopoMapper::jsonResponse(401, 'Unauthenticated'), 401);
        }

        return parent::unauthenticated($request, $exception);
    }
}
