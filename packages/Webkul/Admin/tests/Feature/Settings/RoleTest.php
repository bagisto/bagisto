<?php

use Webkul\User\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    Role::query()->whereNot('id', 1)->delete();
});

it('should returns the role index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.roles.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.index.title'))
        ->assertSeeText(trans('admin::app.settings.roles.index.create-btn'));
});

it('should returns the create page of role', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.roles.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.create.title'))
        ->assertSeeText(trans('admin::app.settings.roles.create.save-btn'));
});

it('should store the newly created roles', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.roles.store'), [
        'name'            => $name = fake()->name(),
        'permission_type' => $permissionType = fake()->randomElement(['custom', 'all']),
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->isRedirection();

    $this->assertDatabaseHas('roles', [
        'name'            => $name,
        'permission_type' => $permissionType,
    ]);
});

it('should returns the edit page of roles', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.edit', $role->id), [
        'name'            => $name = fake()->name(),
        'permission_type' => $permissionType = fake()->randomElement(['custom', 'all']),
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->isRedirection();

    $this->assertDatabaseHas('roles', [
        'name'            => $name,
        'permission_type' => $permissionType,
    ]);
});

it('should update the existing role', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.update', $role->id), [
        'name'            => $name = fake()->name(),
        'permission_type' => $permissionType = fake()->randomElement(['custom', 'all']),
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->isRedirection();

    $this->assertDatabaseHas('roles', [
        'name'            => $name,
        'permission_type' => $permissionType,
    ]);
});

it('should delete the existing role', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.roles.delete', $role->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.roles.delete-success'));

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});
