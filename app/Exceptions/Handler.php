<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponse;
use Error;
use ErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     *
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }

    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse(405, 'The specified method for the request is invalid');
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse(404, 'The specified URL cannot be found');
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getStatusCode(), $exception->getMessage());
        }

        if ($exception instanceof ErrorException || $exception instanceof Error || $exception instanceof PDOException) {
            return $this->errorResponse(500, $exception->getMessage());
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return $this->errorResponse(500, 'Unexpected exception');
    }
}
