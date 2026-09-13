<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;

dataset('acl governed routes', fn () => aclGovernedRoutes());

// ============================================================================
// Access Denied
// ============================================================================

it('should deny an admin holding every permission except the one governing the route', function (string $routeName, string $permission) {
    $this->loginAsAdminWithPermissions(
        collect(config('acl'))->pluck('key')->reject(fn ($key) => $key === $permission)->values()->all()
    );

    requestAclRoute($routeName)->assertUnauthorized();
})->with('acl governed routes');

// ============================================================================
// Access Granted
// ============================================================================

it('should not deny an admin holding the permission governing the route and its ancestors', function (string $routeName, string $permission) {
    Http::fake();

    $this->loginAsAdminWithPermissions(permissionWithAncestors($permission));

    assertNotDeniedByAcl(requestAclRoute($routeName));
})->with('acl governed routes');

/**
 * Every route named in acl.php with the key that governs it, the last entry naming a route winning as the bouncer resolves it.
 */
function aclGovernedRoutes(): array
{
    $routes = [];

    foreach (require dirname(__DIR__, 3).'/src/Config/acl.php' as $entry) {
        foreach (Arr::wrap($entry['route'] ?? []) as $routeName) {
            $routes[$routeName] = [$routeName, $entry['key']];
        }
    }

    return $routes;
}

/**
 * The permission together with every ancestor key, the way the roles form grants a leaf.
 */
function permissionWithAncestors(string $permission): array
{
    $segments = explode('.', $permission);

    return array_map(fn ($depth) => implode('.', array_slice($segments, 0, $depth)), range(1, count($segments)));
}

/**
 * Call the named route with its own verb, every parameter set to an id no row can have.
 */
function requestAclRoute(string $routeName): TestResponse
{
    $route = Route::getRoutes()->getByName($routeName);

    return test()->json($route->methods()[0], route($routeName, array_fill_keys($route->parameterNames(), PHP_INT_MAX)));
}

/**
 * Assert the bouncer let the request through, whatever the controller then decided.
 */
function assertNotDeniedByAcl(TestResponse $response): void
{
    expect($response->status())->not->toBeIn([401, 403]);

    expect((string) $response->getContent())->not->toContain('This action is unauthorized');
}
