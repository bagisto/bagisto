<?php

namespace Webkul\Admin\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\PDOException;
use Illuminate\Database\Eloquent\ErrorException;

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
            $statusCode = $exception->getStatusCode();
            if (strpos($_SERVER['REQUEST_URI'], 'admin') !== false) {
                switch ($statusCode) {
                    case 404:
                        return response()->view('admin::errors.404', [], 404);
                        break;
                    case 403:
                        return response()->view('admin::errors.403', [], 403);
                        break;
                    case 401:
                        return response()->view('admin::errors.401', [], 401);
                        break;
                    default:
                        return response()->view('admin::errors.500', [], 500);
                }
            } else {
                switch ($statusCode) {
                    case 404:
                        return response()->view('shop::errors.404', [], 404);
                        break;
                    case 403:
                        return response()->view('shop::errors.403', [], 403);
                        break;
                    case 401:
                        return response()->view('shop::errors.401', [], 401);
                        break;
                    default:
                        return response()->view('shop::errors.500', [], 500);
                }
            }
        } else if ($exception instanceof \ModelNotFoundException) {
            if (strpos($_SERVER['REQUEST_URI'], 'admin') !== false){
                return response()->view('admin::errors.404', [], 404);
            }else {
                return response()->view('shop::errors.404', [], 404);
            }
        } else if ($exception instanceof \PDOException) {
            if (strpos($_SERVER['REQUEST_URI'], 'admin') !== false){
                return response()->view('admin::errors.500', [], 500);
            } else {
                return response()->view('shop::errors.500', [], 500);
            }
        }
        // else if ($exception instanceof \ErrorException) {

        //     if(strpos($_SERVER['REQUEST_URI'], 'admin') !== false){
        //         return response()->view('admin::errors.500', [], 500);
        //     }else {
        //         return response()->view('shop::errors.500', [], 500);
        //     }
        // }

        return parent::render($request, $exception);
    }
}