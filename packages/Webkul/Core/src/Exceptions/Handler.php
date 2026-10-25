<?php

namespace Webkul\Core\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends BaseHandler
{
    /**
     * The statuses Bagisto answers with an error page of its own.
     */
    const ERROR_PAGE_STATUSES = [401, 403, 404, 503];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        if (config('app.debug')) {
            return;
        }

        $this->handleAuthenticationException();

        $this->handleHttpException();

        $this->handleValidationException();

        $this->handleServerException();
    }

    /**
     * Handle the authentication exception.
     */
    protected function handleAuthenticationException(): void
    {
        $this->renderable(function (AuthenticationException $exception, Request $request) {
            $namespace = is_admin_request($request) ? 'admin' : 'shop';

            if ($request->wantsJson()) {
                return response()->json(['error' => trans("{$namespace}::app.errors.401.description")], 401);
            }

            if ($namespace !== 'admin') {
                return redirect()->guest(route('shop.customer.session.index'));
            }

            return redirect()->guest(route('admin.session.create'));
        });
    }

    /**
     * Handle the http exceptions, keeping their headers, such as maintenance's Retry-After, and the status of a
     * client error Bagisto has no page for instead of flattening it into a 500, as with an expired CSRF token's 419.
     */
    protected function handleHttpException(): void
    {
        $this->renderable(function (HttpException $exception, Request $request) {
            $namespace = is_admin_request($request) ? 'admin' : 'shop';

            $statusCode = $exception->getStatusCode();

            if (
                $statusCode >= 400
                && $statusCode < 500
                && ! in_array($statusCode, self::ERROR_PAGE_STATUSES)
            ) {
                return $this->shouldReturnJson($request, $exception)
                    ? $this->prepareJsonResponse($request, $exception)
                    : $this->renderHttpException($exception);
            }

            $errorCode = in_array($statusCode, self::ERROR_PAGE_STATUSES)
                ? $statusCode
                : 500;

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => trans("{$namespace}::app.errors.{$errorCode}.title"),
                    'description' => trans("{$namespace}::app.errors.{$errorCode}.description"),
                ], $errorCode, $exception->getHeaders());
            }

            $viewPath = "{$namespace}::errors.{$errorCode}";

            if (! view()->exists($viewPath)) {
                $viewPath = "{$namespace}::errors.index";
            }

            return response()->view($viewPath, compact('errorCode'), $errorCode, $exception->getHeaders());
        });
    }

    /**
     * Handle validation exceptions.
     */
    protected function handleValidationException(): void
    {
        $this->renderable(function (ValidationException $exception, Request $request) {
            return parent::convertValidationExceptionToResponse($exception, $request);
        });
    }

    /**
     * Handle the server exceptions.
     */
    protected function handleServerException(): void
    {
        $this->renderable(function (Throwable $throwable, Request $request) {
            $namespace = is_admin_request($request) ? 'admin' : 'shop';

            $errorCode = 500;

            if ($request->wantsJson()) {
                return response()->json([
                    'error' => trans("{$namespace}::app.errors.{$errorCode}.title"),
                    'description' => trans("{$namespace}::app.errors.{$errorCode}.description"),
                ], $errorCode);
            }

            $viewPath = "{$namespace}::errors.{$errorCode}";

            if (! view()->exists($viewPath)) {
                $viewPath = "{$namespace}::errors.index";
            }

            return response()->view($viewPath, compact('errorCode'), $errorCode);
        });
    }
}
