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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $path = $this->isAdminUri() ? 'admin' : 'shop';

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $statusCode = in_array($statusCode, [401, 403, 404, 503]) ? $statusCode : 500;
            return $this->response($path, $statusCode);
        } else if ($exception instanceof ModelNotFoundException) {
            return $this->response($path, 404);
        } else if ($exception instanceof PDOException) {
            return $this->response($path, 500);
        }

        return parent::render($request, $exception);
    }

    private function isAdminUri()
    {
        return strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? true : false;
    }

    private function response($path, $statusCode)
    {
        return response()->view("{$path}::errors.{$statusCode}", [], $statusCode);
    }

}