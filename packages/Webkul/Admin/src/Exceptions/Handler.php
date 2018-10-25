<?php

namespace Webkul\Admin\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();

            if(strpos($_SERVER['REQUEST_URI'], 'admin') !== false) {
                return response(view('admin::errors.' . $statusCode, [
                    'msg' => $exception->getMessage(),
                    'code' => $statusCode
                ]), $statusCode);
            } else {
                return response(view('shop::errors.' . $statusCode, [
                    'msg' => $exception->getMessage(),
                    'code' => $statusCode
                ]), $statusCode);
            }
        }

        return parent::render($request, $exception);
    }
}