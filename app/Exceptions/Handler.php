<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $statusCode = 200;

    public function setStatusCode($value)
    {
        $this->statusCode = $value >= 100 ? $value : 500;
    }

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
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();
        $this->setStatusCode($code);
        if (strpos($request->url(), '/api/') !== false) {
            if ($exception instanceof UnauthorizedHttpException) {
                switch (get_class($exception)) {
                    case TokenExpiredException::class:
                        return $this->respondWithError(__('api.messages.token_expired'));
                    case TokenBlacklistedException::class:
                        return $this->respondWithError(__('api.messages.token_blacklist'));
                    case TokenInvalidException::class:
                        return $this->respondWithError(__('api.messages.token_invalid'));
                    default:
                        return $this->respondWithError($exception->getMessage());
                }
            }

            if ($exception instanceof AuthorizationException) {
                return $this->respondWithError($exception->getMessage());
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return $this->respondWithError(__('api.messages.method_not_allowed'));
            }

            if ($exception instanceof NotFoundHttpException) {
                return $this->respondWithError(__('api.messages.url_not_found'));
            }

            if ($exception instanceof ModelNotFoundException) {
                return $this->respondWithError(__('api.messages.model_not_found'));
            }

            if ($exception instanceof ValidationException) {
                return $this->respondWithError($exception->validator->messages());
            }

            if ($exception instanceof Exception) {
                return $this->respondWithError($exception->getMessage());
            }
        }

        return parent::render($request, $exception);
    }

    /**
     * Respond with error.
     *
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'code' => $this->statusCode,
            ],
        ]);
    }

    /**
     * Respond.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }
}
