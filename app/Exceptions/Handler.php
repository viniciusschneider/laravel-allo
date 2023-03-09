<?php

namespace App\Exceptions;

use App\Traits\ErrorsExceptionsTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ErrorsExceptionsTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function(TokenInvalidException $e) {
            $this->unauthorizedRequestException('Invalid token');
        });

        $this->renderable(function (TokenExpiredException $e) {
            $this->unauthorizedRequestException('Token has expired');
        });

        $this->renderable(function (JWTException $e) {
            $this->unauthorizedRequestException('Token not parsed');
        });

        $this->renderable(function (NotFoundHttpException $e) {
            $this->notFoundRequestException();
        });

        $this->renderable(function (ThrottleRequestsException $e) {
            $this->tooManyRequestsRequestException();
        });
    }
}
