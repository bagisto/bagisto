<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Webkul\User\Models\Admin;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should returns the user index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.users.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.index.title'))
        ->assertSeeText(trans('admin::app.settings.users.index.create.title'));
});

it('should fail the validation with errors when certain field not provided when store the users', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('role_id')
        ->assertUnprocessable();
});

it('should fail the validation with errors when confirm password not provided when store the users', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), [
        'password' => 'admin123',
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('role_id')
        ->assertJsonValidationErrorFor('password_confirmation')
        ->assertUnprocessable();
});

it('should store the newly created admin', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), $data = [
        'name'                  => fake()->name(),
        'role_id'               => 1,
        'email'                 => fake()->email,
        'password'              => $password = fake()->password(),
        'password_confirmation' => $password,
        'image'                 => [
            UploadedFile::fake()->image('avatar.jpg'),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.create-success'));

    $this->assertModelWise([
        Admin::class => [
            [
                'name'    => $data['name'],
                'email'   => $data['email'],
                'role_id' => 1,
            ],
        ],
    ]);
});

it('should fails the validation error with tempered avatar provided when store the admin', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), [
        'name'                  => fake()->name(),
        'role_id'               => 1,
        'email'                 => fake()->email(),
        'password'              => $password = fake()->password(),
        'password_confirmation' => $password,
        'image'                 => [
            UploadedFile::fake()->image('avatar.php'),
        ],
    ])
        ->assertJsonValidationErrorFor('image.0')
        ->assertUnprocessable();
});

it('should returns the user and its roles', function () {
    // Arrange.
    $admin = Admin::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.settings.users.edit', $admin->id))
        ->assertOk()
        ->assertJsonPath('roles.0.id', 1)
        ->assertJsonPath('roles.0.name', 'Administrator')
        ->assertJsonPath('user.id', $admin->id)
        ->assertJsonPath('user.email', $admin->email);
});

it('should fail the validation with errors when certain field not provided when update the users', function () {
    // Arrange.
    $admin = Admin::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), [
        'id'       => $admin->id,
        'password' => 'admin123',
    ])
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('role_id')
        ->assertJsonValidationErrorFor('password_confirmation')
        ->assertUnprocessable();
});

it('should update the existing admin', function () {
    // Arrange.
    $admin = Admin::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), $data = [
        'id'                    => $admin->id,
        'name'                  => $admin->name,
        'image'                 => [
            UploadedFile::fake()->image('avatar.jpg'),
        ],
        'role_id'               => 1,
        'email'                 => fake()->email(),
        'password'              => $password = fake()->password(),
        'password_confirmation' => $password,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.update-success'));

    $this->assertModelWise([
        Admin::class => [
            Arr::except($data, ['image', 'password_confirmation', 'password']),
        ],
    ]);
});

it('should fails the validation error with tempered avatar provided when update the admin', function () {
    // Arrange.
    $admin = Admin::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), [
        'id'                    => $admin->id,
        'name'                  => $admin->name,
        'image'                 => [
            UploadedFile::fake()->image('avatar.php'),
        ],
        'role_id'               => 1,
        'email'                 => fake()->email(),
        'password'              => $password = fake()->password(),
        'password_confirmation' => $password,
    ])
        ->assertJsonValidationErrorFor('image.0')
        ->assertUnprocessable();
});

it('should delete the existing admin', function () {
    // Arrange.
    $admin = Admin::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.settings.users.delete', $admin->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.delete-success'));

    $this->assertDatabaseMissing('admins', [
        'id' => $admin->id,
    ]);
});

it('should delete self admin', function () {
    // Arrange.
    $admin = Admin::factory()->create([
        'password' => Hash::make($password = fake()->password()),
    ]);

    // Act and Assert.
    $this->loginAsAdmin($admin);

    putJson(route('admin.settings.users.destroy'), [
        'password' => $password,
    ])
        ->assertOk()
        ->assertJsonPath('redirectUrl', route('admin.session.create'))
        ->assertJsonPath('message', trans('admin::app.settings.users.delete-success'));
});
