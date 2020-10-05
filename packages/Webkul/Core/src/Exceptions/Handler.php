<?php

namespace Webkul\Core\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\Handler as AppExceptionHandler;

class Handler extends AppExceptionHandler
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
     * @param  \Throwable   $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $path = $this->isAdminUri() ? 'admin' : 'shop';

        if ($exception instanceof HttpException) {
            $statusCode = in_array($exception->getStatusCode(), [401, 403, 404, 503]) ? $exception->getStatusCode() : 500;

            return $this->response($path, $statusCode);
        } elseif ($exception instanceof ModelNotFoundException) {
            return $this->response($path, 404);
        } elseif ($exception instanceof PDOException) {
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

        return redirect()->guest(route('customer.session.index'));
    }

    private function isAdminUri()
    {
        return strpos(\Illuminate\Support\Facades\Request::path(), 'admin') !== false ? true : false;
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