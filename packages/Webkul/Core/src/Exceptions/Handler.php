<?php

namespace Webkul\Core\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Request;
use App\Exceptions\Handler as BaseHandler;
use PDOException;
use Throwable;

class Handler extends BaseHandler
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
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $path = $this->isAdminUri() ? 'admin' : 'shop';

        if ($request->expectsJson()) {
            return response()->json(['error' => trans("{$path}::app.errors.401.description")], 401);
        }

        if ($path == 'admin') {
            return redirect()->guest(route('admin.session.create'));
        } else {
            return redirect()->guest(route('shop.customer.session.index'));
        }
    }

    /**
     * Is admin uri.
     *
     * @return boolean
     */
    private function isAdminUri()
    {
        return strpos(Request::path(), 'admin') !== false;
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
        $path = $this->isAdminUri() ? 'admin' : 'shop';

        if ($exception instanceof HttpException) {
            $statusCode = in_array($exception->getStatusCode(), [401, 403, 404, 503])
                ? $exception->getStatusCode()
                : 500;

            return $this->response($path, $statusCode);
        } elseif ($exception instanceof ModelNotFoundException) {
            return $this->response($path, 404);
        } elseif ($exception instanceof PDOException) {
            return $this->response($path, 500);
        } else {
            return parent::render($request, $exception);
        }
    }

    /**
     * Response.
     *
     * @param  string  $path
     * @param  int  $errorCode
     * @return \Illuminate\Http\Response
     */
    private function response($path, $errorCode)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'error' => trans("{$path}::app.errors.{$errorCode}.description"),
            ], $errorCode);
        }

        return response()->view("{$path}::errors.index", compact('errorCode'));
    }
}
