<?php

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;
use Illuminate\Http\Request;

// ============================================================================
// Admin Requests
// ============================================================================

it('should take a request for the admin panel by the leading segment of its path', function (string $uri, bool $isAdmin) {
    config(['app.admin_url' => 'admin']);

    expect(is_admin_request(Request::create($uri)))->toBe($isAdmin);
})->with([
    'admin root' => ['/admin', true],
    'admin page' => ['/admin/catalog/products', true],
    'encoded admin page' => ['/%61dmin/dashboard', true],
    'storefront home page' => ['/', false],
    'storefront page whose query holds the segment' => ['/search?query=admin/x', false],
    'cached image whose path holds the segment' => ['/cache/large/products/admin/x.png', false],
    'path that only begins with the admin url letters' => ['/administrator', false],
]);

it('should read the admin url without its slashes', function () {
    config(['app.admin_url' => '/backend/']);

    expect(is_admin_request(Request::create('/backend/dashboard')))->toBeTrue()
        ->and(is_admin_request(Request::create('/admin/dashboard')))->toBeFalse();
});

it('should take no request for the admin panel when no admin url is set', function (?string $adminUrl) {
    config(['app.admin_url' => $adminUrl]);

    expect(is_admin_request(Request::create('/')))->toBeFalse();
})->with([
    'unset' => [null],
    'empty' => [''],
    'only a slash' => ['/'],
]);

// ============================================================================
// Maintenance Mode
// ============================================================================

it('should let the admin url and every path under it through maintenance, as down writes them into the down file', function (?string $adminUrl, array $adminPaths) {
    config(['app.admin_url' => $adminUrl]);

    $laravelPaths = (new PreventRequestsDuringMaintenance(app()))->getExcludedPaths();

    expect(app(PreventRequestsDuringMaintenance::class)->getExcludedPaths())->toBe([...$laravelPaths, ...$adminPaths]);
})->with([
    'admin url' => ['admin', ['admin', 'admin/*']],
    'admin url written with slashes' => ['/backend/', ['backend', 'backend/*']],
    'no admin url' => ['', []],
]);
