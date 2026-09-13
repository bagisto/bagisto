<?php

use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

/**
 * An admin whose role carries only the given permissions, and so never reaches the
 * dashboard the sign in would otherwise land on.
 */
function adminLimitedTo(array $permissions): Admin
{
    $role = Role::factory()->create([
        'permission_type' => 'custom',
        'permissions' => $permissions,
    ]);

    return Admin::factory()->create([
        'password' => bcrypt('admin123'),
        'status' => 1,
        'role_id' => $role->id,
    ]);
}

/**
 * Sign an admin in through the login form and carry the session cookie into the requests that
 * follow, the way a browser would.
 */
function signInThroughForm(): Admin
{
    $admin = Admin::factory()->create([
        'password' => bcrypt('admin123'),
        'status' => 1,
    ]);

    post(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])->assertRedirect(route('admin.dashboard.index'));

    test()->assertAuthenticatedAs($admin, 'admin');

    test()->withCookie(config('session.cookie'), session()->getId());

    return $admin;
}

// ============================================================================
// Landing Route
// ============================================================================

it('should sign in an admin whose first permission names several routes', function (array $permissions, string $route) {
    $admin = adminLimitedTo($permissions);

    post(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])->assertRedirect(route($route));

    $this->assertAuthenticatedAs($admin, 'admin');
})->with([
    'catalog' => [['catalog', 'catalog.products'], 'admin.catalog.products.index'],
    'appearance' => [['appearance', 'appearance.themes'], 'admin.appearance.themes.index'],
    'configuration' => [['configuration'], 'admin.configuration.index'],
]);

it('should fall back rather than fail when a permission has nowhere to land', function () {
    $admin = adminLimitedTo(['appearance.sections.create']);

    post(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])->assertRedirect(route('admin.dashboard.index'));

    $this->assertAuthenticatedAs($admin, 'admin');
});

it('should send an admin who can see the dashboard to it', function () {
    $admin = adminLimitedTo(['dashboard']);

    post(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])->assertRedirect(route('admin.dashboard.index'));

    $this->assertAuthenticatedAs($admin, 'admin');
});

// ============================================================================
// Logout
// ============================================================================

it('should invalidate the session and regenerate the csrf token on logout', function () {
    signInThroughForm();

    $sessionId = session()->getId();

    $token = session()->token();

    expect(session()->has(auth()->guard('admin')->getName()))->toBeTrue();

    delete(route('admin.session.destroy'))->assertRedirect(route('admin.session.create'));

    $this->assertGuest('admin');

    expect(session()->getId())->not->toBe($sessionId)
        ->and(session()->token())->not->toBe($token)
        ->and(session()->has(auth()->guard('admin')->getName()))->toBeFalse();
});

it('should treat a request carrying the old session as a guest after logout', function () {
    signInThroughForm();

    delete(route('admin.session.destroy'))->assertRedirect(route('admin.session.create'));

    app('auth')->forgetGuards();

    get(route('admin.dashboard.index'))->assertRedirect(route('admin.session.create'));

    $this->assertGuest('admin');
});
