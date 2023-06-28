<?php

namespace Webkul\Shop\Exceptions;

use Webkul\Core\Exceptions\Handler as WebkulCoreHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PDOException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends WebkulCoreHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if (! config('app.debug')) {
            return $this->renderCustomResponse($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render custom HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|null
     */
    private function renderCustomResponse($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            return $this->response($exception->getStatusCode());
        } elseif ($exception instanceof ModelNotFoundException) {
            return $this->response(404);
        } elseif ($exception instanceof PDOException) {
            return $this->response(500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Response.
     *
     * @param  string  $path
     * @param  int  $statusCode
     * @return \Illuminate\Http\Response
     */
    private function response($statusCode)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'error' => $this->jsonErrorMessages[$statusCode] ?? 'Something went wrong, please try again later.',
            ], $statusCode);
        }

        return response()->view("shop::errors.index", [], $statusCode);
    }
}
