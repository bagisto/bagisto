<?php

use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Webkul\Core\Helpers\RevokedSessions;
use Webkul\Core\Helpers\SessionPurger;

beforeEach(function () {
    Cache::flush();

    config(['session.driver' => 'database']);

    DB::table('sessions')->delete();
});

/**
 * The session key Laravel writes when the given guard signs a user in.
 */
function purgedGuardKey(string $guard): string
{
    return 'login_'.$guard.'_'.sha1(SessionGuard::class);
}

/**
 * Store a session row signing the given user in under that guard, and return its identifier.
 */
function storedSessionFor(string $label, string $guard, int $userId): string
{
    $identifier = str_pad($label, 40, 'x');

    DB::table('sessions')->insert([
        'id' => $identifier,
        'user_id' => $userId,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'probe',
        'payload' => base64_encode(serialize([purgedGuardKey($guard) => $userId])),
        'last_activity' => now()->getTimestamp(),
    ]);

    return $identifier;
}

// ============================================================================
// Revoking What It Drops
// ============================================================================

it('should revoke every session it drops for the user', function () {
    $first = storedSessionFor('phone', 'customer', 42);
    $second = storedSessionFor('laptop', 'customer', 42);

    app(SessionPurger::class)->forget('customer', 42);

    $revoked = app(RevokedSessions::class);

    expect(DB::table('sessions')->whereIn('id', [$first, $second])->count())->toBe(0)
        ->and($revoked->has($first))->toBeTrue()
        ->and($revoked->has($second))->toBeTrue();
});

it('should not revoke the session the reset is made from', function () {
    $current = storedSessionFor('current', 'customer', 42);
    $other = storedSessionFor('other', 'customer', 42);

    session()->setId($current);

    app(SessionPurger::class)->forget('customer', 42);

    $revoked = app(RevokedSessions::class);

    expect($revoked->has($current))->toBeFalse()
        ->and($revoked->has($other))->toBeTrue();
});

// ============================================================================
// Leaving Everything Else Alone
// ============================================================================

it('should leave the sessions of another user untouched', function () {
    $theirs = storedSessionFor('theirs', 'customer', 99);

    app(SessionPurger::class)->forget('customer', 42);

    expect(DB::table('sessions')->where('id', $theirs)->count())->toBe(1)
        ->and(app(RevokedSessions::class)->has($theirs))->toBeFalse();
});

it('should leave a session signed in under a different guard untouched', function () {
    $admin = storedSessionFor('admin', 'admin', 42);

    app(SessionPurger::class)->forget('customer', 42);

    expect(DB::table('sessions')->where('id', $admin)->count())->toBe(1)
        ->and(app(RevokedSessions::class)->has($admin))->toBeFalse();
});

it('should do nothing when sessions are not stored in the database', function () {
    $stored = storedSessionFor('filed', 'customer', 42);

    config(['session.driver' => 'file']);

    app(SessionPurger::class)->forget('customer', 42);

    expect(DB::table('sessions')->where('id', $stored)->count())->toBe(1)
        ->and(app(RevokedSessions::class)->has($stored))->toBeFalse();
});
