<?php

use Webkul\User\Models\Role;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the role index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.roles.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.index.title'))
        ->assertSeeText(trans('admin::app.settings.roles.index.create-btn'));
});

it('should returns the create page of role', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.roles.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.roles.create.title'))
        ->assertSeeText(trans('admin::app.settings.roles.create.save-btn'));
});

it('should fail the validation with errors when certain field not provided when store the roles', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.roles.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('permission_type')
        ->assertUnprocessable();
});

it('should store the newly created roles', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.roles.store'), $data = [
        'name'            => fake()->name(),
        'permission_type' => fake()->randomElement(['custom', 'all']),
        'description'     => fake()->sentence(),
        'permissions'     => [
            0 => acl()->getRoles()->random(),
        ],
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->isRedirection();

    $this->assertModelWise([
        Role::class => [
            [
                'name'            => $data['name'],
                'permission_type' => $data['permission_type'],
                'description'     => $data['description'],
            ],
        ],
    ]);
});

it('should returns the edit page of roles', function () {
    // Arrange.
    $role = Role::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.edit', $role->id), $data = [
        'name'            => fake()->name(),
        'permission_type' => fake()->randomElement(['custom', 'all']),
        'description'     => fake()->sentence(),
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->isRedirection();

    $this->assertModelWise([
        Role::class => [
            [
                'name'            => $data['name'],
                'permission_type' => $data['permission_type'],
                'description'     => $data['description'],
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain field not provided when update the roles', function () {
    // Arrange.
    $role = Role::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.update', $role->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('description')
        ->assertJsonValidationErrorFor('permission_type')
        ->assertUnprocessable();
});

it('should update the existing role', function () {
    // Arrange.
    $role = Role::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.roles.update', $role->id), $data = [
        'name'            => fake()->name(),
        'permission_type' => fake()->randomElement(['custom', 'all']),
        'description'     => fake()->sentence(),
    ])
        ->assertRedirect(route('admin.settings.roles.index'))
        ->isRedirection();

    $this->assertModelWise([
        Role::class => [
            [
                'name'            => $data['name'],
                'permission_type' => $data['permission_type'],
                'description'     => $data['description'],
            ],
        ],
    ]);
});

it('should delete the existing role', function () {
    // Arrange.
    $role = Role::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.roles.delete', $role->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.settings.roles.delete-success'));

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});
