<?php

namespace Webkul\Core\Exceptions;

use App\Exceptions\Handler as AppExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends AppExceptionHandler
{
    /**
     * Json errors.
     *
     * @var array
     */
    protected $jsonErrorMessages = [
        404 => 'Resource Not Found',
        403 => '403 Forbidden Error',
        401 => 'Unauthenticated',
        500 => '500 Internal Server Error',
    ];

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
        if ($request->expectsJson()) {
            return response()->json(['error' => $this->jsonErrorMessages[401]], 401);
        }

        return redirect()->guest(route('shop.customer.session.index'));
    }

    /**
     * Is admin uri.
     *
     * @return boolean
     */
    private function isAdminUri()
    {
        return strpos(\Illuminate\Support\Facades\Request::path(), 'admin') !== false;
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
     * @param  int  $statusCode
     * @return \Illuminate\Http\Response
     */
    private function response($path, $statusCode)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'error' => $this->jsonErrorMessages[$statusCode] ?? 'Something went wrong, please try again later.',
            ], $statusCode);
        }

        return response()->view("{$path}::errors.{$statusCode}", [], $statusCode);
    }
}
