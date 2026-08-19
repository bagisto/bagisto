<?php

use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

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

it('should sign in an admin whose first permission names several routes', function (array $permissions, string $route) {
    $admin = adminLimitedTo($permissions);

    post(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])->assertRedirect(route($route));
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
});

it('should send an admin who can see the dashboard to it', function () {
    $admin = adminLimitedTo(['dashboard']);

    post(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])->assertRedirect(route('admin.dashboard.index'));
});
