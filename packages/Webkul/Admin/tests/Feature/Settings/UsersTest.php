<?php

use Illuminate\Support\Facades\Hash;
use Webkul\User\Models\Admin;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    Admin::query()->whereNot('id', 1)->delete();
});

it('should returns the user index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.users.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.index.title'))
        ->assertSeeText(trans('admin::app.settings.users.index.create.title'));
});

it('should store the newly created user/admin', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), [
        'name'                  => $name = fake()->name(),
        'role_id'               => 1,
        'email'                 => $email = fake()->email,
        'password'              => $password = fake()->password,
        'password_confirmation' => $password,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.create-success'));

    $this->assertDatabaseHas('admins', [
        'name'    => $name,
        'role_id' => 1,
        'email'   => $email,
    ]);
});

it('should returns the user and its roles', function () {
    // Arrange
    $user = Admin::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.settings.users.edit', $user->id))
        ->assertOk()
        ->assertJsonPath('roles.0.id', 1)
        ->assertJsonPath('roles.0.name', 'Administrator')
        ->assertJsonPath('user.id', $user->id)
        ->assertJsonPath('user.email', $user->email);
});

it('should update the existing user/admin', function () {
    // Arrange
    $user = Admin::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), [
        'id'                    => $user->id,
        'name'                  => $user->name,
        'role_id'               => 1,
        'email'                 => $email = fake()->email,
        'password'              => $password = fake()->password,
        'password_confirmation' => $password,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.update-success'));

    $this->assertDatabaseHas('admins', [
        'id'      => $user->id,
        'name'    => $user->name,
        'role_id' => 1,
        'email'   => $email,
    ]);
});

it('should delete the existing user/admin', function () {
    // Arrange
    $user = Admin::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.users.delete', $user->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.delete-success'));

    $this->assertDatabaseMissing('admins', [
        'id' => $user->id,
    ]);
});

it('should delete self user/admin', function () {
    // Arrange
    $admin = Admin::factory()->create([
        'password' => Hash::make($password = fake()->password()),
    ]);

    // Act and Assert
    $this->loginAsAdmin($admin);

    putJson(route('admin.settings.users.destroy'), [
        'password' => $password,
    ])
        ->assertOk()
        ->assertJsonPath('redirectUrl', route('admin.session.create'))
        ->assertJsonPath('message', trans('admin::app.settings.users.delete-success'));
});
