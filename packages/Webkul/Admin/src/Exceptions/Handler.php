<?php

namespace Webkul\Admin\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\PDOException;
use Illuminate\Database\Eloquent\ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $jsonErrorMessages = [
        404 => 'Resource not found',
        403 => '403 forbidden Error',
        401 => 'Unauthenticated',
        500 => '500 Internal Server Error',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $path = 'admin';

        if ($exception instanceof HttpException) {
            $statusCode = in_array($exception->getStatusCode(), [401, 403, 404, 503]) ? $exception->getStatusCode() : 500;

            return $this->response($path, $statusCode);
        } else if ($exception instanceof ModelNotFoundException) {
            return $this->response($path, 404);
        } else if ($exception instanceof PDOException) {
            return $this->response($path, 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $this->jsonErrorMessages[401]], 401);
        }

        return redirect()->guest(route('auth.login'));
    }

    private function isAdminUri()
    {
        return strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? true : false;
    }

    private function response($path, $statusCode)
    {
        if (request()->expectsJson()) {
            return response()->json([
                    'error' => isset($this->jsonErrorMessages[$statusCode])
                        ? $this->jsonErrorMessages[$statusCode]
                        : 'Something went wrong, please try again later.'
                ], $statusCode);
        }

        return response()->view("{$path}::errors.{$statusCode}", [], $statusCode);
    }
}