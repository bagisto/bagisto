<?php

namespace Webkul\SAASCustomizer\Exceptions;

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
        $path = 'saas';

        if ($exception->getMessage() == 'domain_not_found') {
            return $this->response($path, 400, trans('saas::app.exceptions.domain-not-found'), 'domain_not_found');
        }

        if ($exception->getMessage() == 'company_blocked_by_administrator') {
            return $this->response($path, 404, trans('saas::app.exceptions.company-blocked-by-administrator'), 'company_blocked_by_administrator');
        }

        if ($exception->getMessage() == 'not_allowed_to_visit_this_section') {
            return $this->response($path, 400, trans('saas::app.exceptions.not-allowed-to-visit-this-section'), 'not_allowed_to_visit_this_section');
        }

        if ($exception->getMessage() == 'illegal_action') {
            return $this->response($path, 400, trans('saas::app.exceptions.illegal-action'), 'illegal_action');
        }

        if ($exception->getMessage() == 'invalid_admin_login' || $exception->getMessage() == 'invalid_customer_login') {
            return $this->response($path, 404, trans('saas::app.exceptions.auth'));
        }

        if ($exception instanceof HttpException) {
            $statusCode = in_array($exception->getStatusCode(), [401, 403, 404, 503]) ? $exception->getStatusCode() : 500;

            return $this->response($path, $statusCode);
        } else if ($exception instanceof ModelNotFoundException) {
            return $this->response($path, 404);
        } else if ($exception instanceof PDOException) {
            return $this->response($path, 500);
        } else {
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

        return redirect()->guest(route('super.session.index'));
    }

    private function isAdminUri()
    {
        return strpos($_SERVER['REQUEST_URI'], 'super') !== false ? true : false;
    }

    private function response($path, $statusCode, $message = null, $type = null)
    {
        if (request()->expectsJson()) {
            return response()->json([
                    'error' => isset($this->jsonErrorMessages[$statusCode])
                        ? $this->jsonErrorMessages[$statusCode]
                        : trans('saas::app.status.something-wrong-1')
                ], $statusCode);
        }

        if ($type == null) {
            return response()->view("{$path}::errors.{$statusCode}", ['message' => $message, 'status' => $statusCode], $statusCode);
        } else {
            return response()->view("{$path}::errors.{$type}", ['message' => $message, 'status' => $statusCode], $statusCode);
        }
    }
}