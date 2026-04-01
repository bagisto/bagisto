<?php

use Webkul\User\Models\Admin;
use Webkul\User\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the role index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.roles.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.index.title'))
        ->assertSeeText(trans('admin::app.settings.roles.index.create-btn'));
});

it('should deny guest access to the role index page', function () {
    get(route('admin.settings.roles.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the role create page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.roles.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created role', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.roles.store'), $data = [
        'name' => fake()->words(2, true),
        'permission_type' => fake()->randomElement(['custom', 'all']),
        'description' => fake()->sentence(),
        'permissions' => [acl()->getRoles()->random()],
    ])
        ->assertRedirect(route('admin.settings.roles.index'));

    $this->assertDatabaseHas('roles', [
        'name' => $data['name'],
        'permission_type' => $data['permission_type'],
        'description' => $data['description'],
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.roles.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('permission_type');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the role edit page', function () {
    $role = Role::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.roles.edit', $role->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing role', function () {
    $role = Role::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.update', $role->id), $data = [
        'name' => fake()->words(2, true),
        'permission_type' => fake()->randomElement(['custom', 'all']),
        'description' => fake()->sentence(),
    ])
        ->assertRedirect(route('admin.settings.roles.index'));

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => $data['name'],
        'permission_type' => $data['permission_type'],
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $role = Role::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.update', $role->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('permission_type');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a role', function () {
    $role = Role::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.roles.delete', $role->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.roles.delete-success'));

    $this->assertDatabaseMissing('roles', ['id' => $role->id]);
});

it('should not delete a role that has admins assigned', function () {
    $role = Role::factory()->create();

    Admin::factory()->create(['role_id' => $role->id]);

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.roles.delete', $role->id))
        ->assertStatus(400)
        ->assertJsonPath('message', trans('admin::app.settings.roles.being-used'));
});
