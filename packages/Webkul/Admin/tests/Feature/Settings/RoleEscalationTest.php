<?php

use Illuminate\Support\Str;
use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function Pest\Laravel\put;
use function Pest\Laravel\putJson;

/**
 * The payload creating an admin with the given role.
 */
function newAdminPayload(Role $role): array
{
    return [
        'name' => fake()->name(),
        'email' => fake()->unique()->safeEmail(),
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
        'role_id' => $role->id,
        'status' => 1,
    ];
}

/**
 * The actions the admin listing offers the signed in admin for the given admin.
 */
function listedActionsForAdmin(Admin $admin): array
{
    return getJson(route('admin.settings.users.index', ['filters' => ['user_id' => [$admin->id]]]), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonCount(1, 'records')
        ->json('records.0.actions');
}

/**
 * The actions the role listing offers the signed in admin for the given role.
 */
function listedActionsForRole(Role $role): array
{
    return getJson(route('admin.settings.roles.index', ['filters' => ['id' => [$role->id]]]), ['X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonCount(1, 'records')
        ->json('records.0.actions');
}

/**
 * The keys of every ACL item in the given tree, parents before their children.
 */
function aclTreeKeys(iterable $items): array
{
    return collect($items)
        ->flatMap(fn ($item) => [$item->key, ...aclTreeKeys($item->children)])
        ->all();
}

beforeEach(function () {
    $this->restrictedRole = Role::factory()->create([
        'permission_type' => 'custom',
        'permissions' => [
            'settings',
            'settings.users',
            'settings.users.create',
            'settings.users.edit',
            'settings.users.delete',
            'settings.roles',
            'settings.roles.create',
            'settings.roles.edit',
            'settings.roles.delete',
        ],
    ]);

    $this->administratorRole = Role::query()->where('permission_type', 'all')->firstOrFail();

    $this->restrictedAdmin = Admin::factory()->create([
        'role_id' => $this->restrictedRole->id,
    ]);
});

// ============================================================================
// Users
// ============================================================================

it('should not let an admin give themselves a role with permissions they do not hold', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    putJson(route('admin.settings.users.update'), [
        'id' => $this->restrictedAdmin->id,
        'name' => $this->restrictedAdmin->name,
        'email' => $this->restrictedAdmin->email,
        'role_id' => $this->administratorRole->id,
        'status' => 1,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('role_id');

    expect($this->restrictedAdmin->fresh()->role_id)->toBe($this->restrictedRole->id);
});

it('should not let an admin create a user with a role with permissions they do not hold', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    $payload = newAdminPayload($this->administratorRole);

    postJson(route('admin.settings.users.store'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('role_id');

    $this->assertDatabaseMissing('admins', ['email' => $payload['email']]);
});

it('should still let an admin create a user with a role within their own permissions', function () {
    $narrowerRole = Role::factory()->create([
        'permission_type' => 'custom',
        'permissions' => ['settings', 'settings.users'],
    ]);

    $this->loginAsAdmin($this->restrictedAdmin);

    $payload = newAdminPayload($narrowerRole);

    postJson(route('admin.settings.users.store'), $payload)
        ->assertOk();

    $this->assertDatabaseHas('admins', [
        'email' => $payload['email'],
        'role_id' => $narrowerRole->id,
    ]);
});

it('should only offer the roles an admin may grant', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    $roleIds = collect(getJson(route('admin.settings.users.edit', $this->restrictedAdmin->id))
        ->assertOk()
        ->json('roles'))
        ->pluck('id');

    expect($roleIds)->toContain($this->restrictedRole->id)
        ->not->toContain($this->administratorRole->id);
});

it('should not let an admin change or delete a user whose role has permissions they do not hold', function () {
    $administrator = Admin::factory()->create([
        'role_id' => $this->administratorRole->id,
    ]);

    $this->loginAsAdmin($this->restrictedAdmin);

    getJson(route('admin.settings.users.edit', $administrator->id))
        ->assertForbidden();

    putJson(route('admin.settings.users.update'), [
        'id' => $administrator->id,
        'name' => $administrator->name,
        'email' => $administrator->email,
        'password' => 'taken-over',
        'password_confirmation' => 'taken-over',
        'role_id' => $this->restrictedRole->id,
        'status' => 1,
    ])
        ->assertForbidden();

    deleteJson(route('admin.settings.users.delete', $administrator->id))
        ->assertForbidden();

    $this->assertDatabaseHas('admins', [
        'id' => $administrator->id,
        'role_id' => $this->administratorRole->id,
        'password' => $administrator->password,
    ]);
});

// ============================================================================
// Roles
// ============================================================================

it('should not let an admin create a role with permissions they do not hold', function (array $role) {
    $name = 'Escalated '.Str::random(10);

    $this->loginAsAdmin($this->restrictedAdmin);

    post(route('admin.settings.roles.store'), ['name' => $name, 'description' => 'Escalated', ...$role])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    $this->assertDatabaseMissing('roles', ['name' => $name]);
})->with([
    'all access' => [['permission_type' => 'all']],
    'permissions outside their own' => [['permission_type' => 'custom', 'permissions' => ['catalog', 'catalog.products']]],
]);

it('should not let an admin widen their own role or change a role above them', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    put(route('admin.settings.roles.update', $this->restrictedRole->id), [
        'name' => $this->restrictedRole->name,
        'description' => 'Restricted',
        'permission_type' => 'all',
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    put(route('admin.settings.roles.update', $this->administratorRole->id), [
        'name' => $this->administratorRole->name,
        'description' => 'Administrator',
        'permission_type' => 'custom',
        'permissions' => ['settings'],
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    deleteJson(route('admin.settings.roles.delete', $this->administratorRole->id))
        ->assertForbidden()
        ->assertJsonPath('message', trans('admin::app.settings.roles.permissions-not-grantable'));

    expect($this->restrictedRole->fresh()->permission_type)->toBe('custom')
        ->and($this->administratorRole->fresh()->permission_type)->toBe('all');
});

// ============================================================================
// Listings
// ============================================================================

it('should only offer the user actions an admin may take in the users listing', function () {
    $administrator = Admin::factory()->create([
        'role_id' => $this->administratorRole->id,
    ]);

    $this->loginAsAdmin($this->restrictedAdmin);

    expect(listedActionsForAdmin($administrator))->toBeEmpty()
        ->and(collect(listedActionsForAdmin($this->restrictedAdmin))->pluck('index')->all())->toBe(['edit', 'delete']);
});

it('should only offer the role actions an admin may take in the roles listing', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    expect(listedActionsForRole($this->administratorRole))->toBeEmpty()
        ->and(listedActionsForRole($this->restrictedRole))->toHaveCount(2);
});

// ============================================================================
// Permission Tree
// ============================================================================

it('should only offer the permissions an admin holds when a role is created', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    expect(aclTreeKeys(bouncer()->getGrantableAclItems()))
        ->toContain('settings', 'settings.users', 'settings.roles.delete')
        ->not->toContain('catalog', 'sales', 'settings.channels');

    get(route('admin.settings.roles.create'))
        ->assertOk()
        ->assertDontSee('<option value="all">', false);
});
