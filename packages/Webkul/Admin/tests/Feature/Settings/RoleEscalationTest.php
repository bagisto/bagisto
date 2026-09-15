<?php

use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function Pest\Laravel\put;
use function Pest\Laravel\putJson;

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

    $this->administratorRole = Role::where('permission_type', 'all')->firstOrFail();

    $this->restrictedAdmin = Admin::factory()->create([
        'role_id' => $this->restrictedRole->id,
    ]);
});

it('should not let an admin give themselves a role with permissions they do not hold', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    putJson(route('admin.settings.users.update'), [
        'id' => $this->restrictedAdmin->id,
        'name' => $this->restrictedAdmin->name,
        'email' => $this->restrictedAdmin->email,
        'role_id' => $this->administratorRole->id,
        'status' => 1,
    ])
        ->assertJsonValidationErrorFor('role_id');

    expect($this->restrictedAdmin->fresh()->role_id)->toBe($this->restrictedRole->id);
});

it('should not let an admin create a user with a role with permissions they do not hold', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    postJson(route('admin.settings.users.store'), [
        'name' => 'Escalated',
        'email' => 'escalated@example.com',
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
        'role_id' => $this->administratorRole->id,
        'status' => 1,
    ])
        ->assertJsonValidationErrorFor('role_id');

    expect(Admin::where('email', 'escalated@example.com')->exists())->toBeFalse();
});

it('should still let an admin create a user with a role within their own permissions', function () {
    $narrowerRole = Role::factory()->create([
        'permission_type' => 'custom',
        'permissions' => ['settings', 'settings.users'],
    ]);

    $this->loginAsAdmin($this->restrictedAdmin);

    postJson(route('admin.settings.users.store'), [
        'name' => 'Narrower',
        'email' => 'narrower@example.com',
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
        'role_id' => $narrowerRole->id,
        'status' => 1,
    ])
        ->assertOk();

    expect(Admin::where('email', 'narrower@example.com')->first()->role_id)->toBe($narrowerRole->id);
});

it('should only offer the roles an admin may grant', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    $roleIds = collect(getJson(route('admin.settings.users.edit', $this->restrictedAdmin->id))
        ->assertOk()
        ->json('roles'))
        ->pluck('id');

    expect($roleIds)->toContain($this->restrictedRole->id);

    expect($roleIds)->not->toContain($this->administratorRole->id);
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

    expect($administrator->fresh()->role_id)->toBe($this->administratorRole->id);
});

it('should not let an admin create a role with permissions they do not hold', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    post(route('admin.settings.roles.store'), [
        'name' => 'All Access',
        'description' => 'Escalated',
        'permission_type' => 'all',
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    post(route('admin.settings.roles.store'), [
        'name' => 'Catalog',
        'description' => 'Escalated',
        'permission_type' => 'custom',
        'permissions' => ['catalog', 'catalog.products'],
    ])
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    expect(Role::whereIn('name', ['All Access', 'Catalog'])->exists())->toBeFalse();
});

it('should not let an admin widen their own role or change a role above them', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    put(route('admin.settings.roles.update', $this->restrictedRole->id), [
        'name' => $this->restrictedRole->name,
        'description' => 'Restricted',
        'permission_type' => 'all',
    ])
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    put(route('admin.settings.roles.update', $this->administratorRole->id), [
        'name' => $this->administratorRole->name,
        'description' => 'Administrator',
        'permission_type' => 'custom',
        'permissions' => ['settings'],
    ])
        ->assertSessionHas('error', trans('admin::app.settings.roles.permissions-not-grantable'));

    deleteJson(route('admin.settings.roles.delete', $this->administratorRole->id))
        ->assertForbidden();

    expect($this->restrictedRole->fresh()->permission_type)->toBe('custom');

    expect($this->administratorRole->fresh()->permission_type)->toBe('all');
});

it('should only offer the user actions an admin may take in the users listing', function () {
    $administrator = Admin::factory()->create([
        'role_id' => $this->administratorRole->id,
    ]);

    $this->loginAsAdmin($this->restrictedAdmin);

    $recordFor = fn (Admin $admin) => getJson(route('admin.settings.users.index', [
        'filters' => ['user_id' => [$admin->id]],
    ]), ['X-Requested-With' => 'XMLHttpRequest'])->assertOk()->json('records.0');

    expect($recordFor($administrator)['actions'])->toBeEmpty();

    expect(collect($recordFor($this->restrictedAdmin)['actions'])->pluck('index')->all())->toBe(['edit', 'delete']);
});

it('should only offer the role actions an admin may take in the roles listing', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    $recordFor = fn (Role $role) => getJson(route('admin.settings.roles.index', [
        'filters' => ['id' => [$role->id]],
    ]), ['X-Requested-With' => 'XMLHttpRequest'])->assertOk()->json('records.0');

    expect($recordFor($this->administratorRole)['actions'])->toBeEmpty();

    expect($recordFor($this->restrictedRole)['actions'])->toHaveCount(2);
});

it('should only offer the permissions an admin holds when a role is created', function () {
    $this->loginAsAdmin($this->restrictedAdmin);

    $flatten = function ($items) use (&$flatten) {
        return collect($items)->flatMap(fn ($item) => [$item->key, ...$flatten($item->children)])->all();
    };

    $keys = $flatten(bouncer()->getGrantableAclItems());

    expect($keys)->toContain('settings', 'settings.users', 'settings.roles.delete');

    expect($keys)->not->toContain('catalog', 'sales', 'settings.channels');

    get(route('admin.settings.roles.create'))
        ->assertOk()
        ->assertDontSee('<option value="all">', false);
});
