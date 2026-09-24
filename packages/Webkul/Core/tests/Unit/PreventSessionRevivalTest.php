<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Cache;
use Webkul\Core\Helpers\RevokedSessions;
use Webkul\Core\Http\Middleware\PreventSessionRevival;

beforeEach(function () {
    Cache::flush();
});

/**
 * A session key of the shape Laravel writes when a guard signs in.
 */
function revivalGuardKey(): string
{
    return 'login_admin_59ba36addc2b2f9401580f014c7f58ea4e30989d';
}

/**
 * A label padded into the forty alphanumeric characters a session identifier has to be.
 */
function revivableSessionId(string $label): string
{
    return str_pad($label, 40, 'x');
}

/**
 * A started session holding the given attributes under the identifier the label names.
 */
function revivableSession(string $label, array $attributes = []): Store
{
    $store = new Store('bagisto_session', new ArraySessionHandler(120), revivableSessionId($label));

    $store->start();

    foreach ($attributes as $key => $value) {
        $store->put($key, $value);
    }

    return $store;
}

/**
 * Run one request through the middleware, optionally changing the session while it is inside.
 */
function passThroughRevivalGuard(Store $session, ?Closure $during = null): Store
{
    $request = Request::create('/admin/dashboard');

    $request->setLaravelSession($session);

    (new PreventSessionRevival(new RevokedSessions))->handle($request, function () use ($session, $during) {
        $during?->__invoke($session);

        return new Response;
    });

    return $session;
}

// ============================================================================
// Turning Away A Revived Session
// ============================================================================

it('should turn away a session written back after it was logged out', function () {
    passThroughRevivalGuard(
        revivableSession('revived', [revivalGuardKey() => 1]),
        fn (Store $session) => $session->forget(revivalGuardKey()),
    );

    $revived = passThroughRevivalGuard(revivableSession('revived', [revivalGuardKey() => 1]));

    expect($revived->get(revivalGuardKey()))->toBeNull()
        ->and($revived->getId())->not->toBe(revivableSessionId('revived'));
});

// ============================================================================
// Leaving Every Other Session Alone
// ============================================================================

it('should leave a session it never logged out signed in', function () {
    passThroughRevivalGuard(revivableSession('steady', [revivalGuardKey() => 1]));

    $again = passThroughRevivalGuard(revivableSession('steady', [revivalGuardKey() => 1]));

    expect($again->get(revivalGuardKey()))->toBe(1)
        ->and($again->getId())->toBe(revivableSessionId('steady'));
});

it('should keep a guest session whose identifier a logged out one once used', function () {
    passThroughRevivalGuard(
        revivableSession('recycled', [revivalGuardKey() => 1]),
        fn (Store $session) => $session->forget(revivalGuardKey()),
    );

    $guest = passThroughRevivalGuard(revivableSession('recycled', ['cart_id' => 7]));

    expect($guest->get('cart_id'))->toBe(7)
        ->and($guest->getId())->toBe(revivableSessionId('recycled'));
});

it('should not revoke a session that signs in during the request', function () {
    passThroughRevivalGuard(
        revivableSession('fresh'),
        fn (Store $session) => $session->put(revivalGuardKey(), 1),
    );

    $next = passThroughRevivalGuard(revivableSession('fresh', [revivalGuardKey() => 1]));

    expect($next->get(revivalGuardKey()))->toBe(1)
        ->and($next->getId())->toBe(revivableSessionId('fresh'));
});
