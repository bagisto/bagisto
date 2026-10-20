<?php

use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Webkul\Core\Exceptions\Handler;

/**
 * The Core exception handler as it is registered when debug mode is off.
 */
function productionExceptionHandler(): Handler
{
    config(['app.debug' => false]);

    return new Handler(app());
}

/**
 * A request to the admin login route, as a browser or as a JSON client sends it.
 */
function adminLoginRequest(bool $json = false): Request
{
    return Request::create(
        '/'.config('app.admin_url').'/login',
        'POST',
        server: $json ? ['HTTP_ACCEPT' => 'application/json'] : [],
    );
}

it('keeps the 419 of a CSRF token mismatch', function () {
    $response = productionExceptionHandler()->render(adminLoginRequest(), new TokenMismatchException('CSRF token mismatch.'));

    expect($response->getStatusCode())->toBe(419);
});

it('keeps the 419 of a CSRF token mismatch for a JSON request', function () {
    $response = productionExceptionHandler()->render(adminLoginRequest(json: true), new TokenMismatchException('CSRF token mismatch.'));

    expect($response->getStatusCode())->toBe(419)
        ->and($response->getData(true)['message'])->toBe('CSRF token mismatch.');
});

it('keeps the 429 of a throttled request', function () {
    $response = productionExceptionHandler()->render(adminLoginRequest(json: true), new TooManyRequestsHttpException);

    expect($response->getStatusCode())->toBe(429);
});

it('still answers a missing page with the 404 error', function () {
    $response = productionExceptionHandler()->render(adminLoginRequest(json: true), new NotFoundHttpException);

    expect($response->getStatusCode())->toBe(404)
        ->and($response->getData(true)['error'])->toBe(trans('admin::app.errors.404.title'));
});

it('still answers any other exception with the 500 error', function () {
    $response = productionExceptionHandler()->render(adminLoginRequest(json: true), new RuntimeException('Something broke.'));

    expect($response->getStatusCode())->toBe(500);
});
