<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Webkul\User\Models\Admin;
use Webkul\User\Repositories\AdminRepository;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * Bind an admin repository whose counting methods answer with the given numbers, so a guard
 * that watches the last admin can be reached whatever the database holds.
 */
function adminRepositoryCounting(array $counts): void
{
    $repository = Mockery::mock(AdminRepository::class.'['.implode(',', array_keys($counts)).']', [app()]);

    foreach ($counts as $method => $count) {
        $repository->shouldReceive($method)->andReturn($count);
    }

    app()->instance(AdminRepository::class, $repository);
}

// ============================================================================
// Index
// ============================================================================

it('should return the user index page', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.users.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.index.title'));
});

it('should deny guest access to the user index page', function () {
    get(route('admin.settings.users.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created admin user', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), $data = [
        'name' => fake()->name(),
        'role_id' => 1,
        'email' => fake()->email(),
        'password' => $password = fake()->password(),
        'password_confirmation' => $password,
        'image' => [
            UploadedFile::fake()->image('avatar.jpg'),
        ],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.create-success'));

    $this->assertDatabaseHas('admins', [
        'name' => $data['name'],
        'email' => $data['email'],
        'role_id' => 1,
    ]);

    expect(Hash::check($password, Admin::query()->where('email', $data['email'])->value('password')))->toBeTrue();
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('role_id');
});

it('should fail validation when password confirmation is missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), [
        'password' => 'admin123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('password_confirmation');
});

it('should fail validation when the email belongs to another admin on store', function () {
    $existing = Admin::factory()->create();

    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), [
        'name' => fake()->name(),
        'role_id' => 1,
        'email' => $existing->email,
        'password' => $password = fake()->password(),
        'password_confirmation' => $password,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('should reject a tampered avatar on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.settings.users.store'), [
        'name' => fake()->name(),
        'role_id' => 1,
        'email' => fake()->email(),
        'password' => $password = fake()->password(),
        'password_confirmation' => $password,
        'image' => [
            UploadedFile::fake()->image('avatar.php'),
        ],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('image.0');
});

// ============================================================================
// Edit
// ============================================================================

it('should return user details and roles for edit', function () {
    $admin = Admin::factory()->create();

    $this->loginAsAdmin();

    get(route('admin.settings.users.edit', $admin->id))
        ->assertOk()
        ->assertJsonPath('user.id', $admin->id)
        ->assertJsonPath('user.email', $admin->email)
        ->assertJsonPath('roles.*.id', fn (array $ids) => in_array($admin->role_id, $ids));
});

it('should return 404 for an admin that does not exist on edit', function () {
    $this->loginAsAdmin();

    get(route('admin.settings.users.edit', 999999))
        ->assertNotFound();
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing admin user', function () {
    $admin = Admin::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), $data = [
        'id' => $admin->id,
        'name' => $admin->name,
        'image' => [
            UploadedFile::fake()->image('avatar.jpg'),
        ],
        'role_id' => 1,
        'email' => fake()->email(),
        'password' => $password = fake()->password(),
        'password_confirmation' => $password,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.update-success'));

    $this->assertDatabaseHas('admins', [
        'id' => $admin->id,
        'name' => $data['name'],
        'email' => $data['email'],
    ]);

    expect(Hash::check($password, $admin->fresh()->password))->toBeTrue();
});

it('should fail validation when required fields are missing on update', function () {
    $admin = Admin::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), [
        'id' => $admin->id,
        'password' => 'admin123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('role_id')
        ->assertJsonValidationErrorFor('password_confirmation');
});

it('should reject a tampered avatar on update', function () {
    $admin = Admin::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), [
        'id' => $admin->id,
        'name' => $admin->name,
        'image' => [
            UploadedFile::fake()->image('avatar.php'),
        ],
        'role_id' => 1,
        'email' => fake()->email(),
        'password' => $password = fake()->password(),
        'password_confirmation' => $password,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('image.0');
});

it('should update a user without a new image', function () {
    $admin = Admin::factory()->create();
    $admin->update(['image' => 'avatar.jpg']);
    $admin->refresh();

    $this->loginAsAdmin($admin);

    putJson(route('admin.settings.users.update'), [
        'id' => $admin->id,
        'name' => $admin->name,
        'image' => ['image' => ''],
        'role_id' => 1,
        'email' => fake()->email(),
        'password' => $password = fake()->password(),
        'password_confirmation' => $password,
    ])
        ->assertOk();

    $this->assertDatabaseHas('admins', [
        'id' => $admin->id,
        'image' => $admin->image,
    ]);
});

it('should not deactivate the only active admin with all access', function () {
    $admin = $this->loginAsAdmin();

    adminRepositoryCounting(['countAdminsWithAllAccessAndActiveStatus' => 1]);

    putJson(route('admin.settings.users.update'), [
        'id' => $admin->id,
        'name' => $admin->name,
        'email' => $admin->email,
        'role_id' => $admin->role_id,
        'password' => '',
    ])
        ->assertSessionHas('error', trans('admin::app.settings.users.cannot-change', ['name' => 'status']));

    $this->assertDatabaseHas('admins', [
        'id' => $admin->id,
        'status' => 1,
    ]);
});

it('should not move the only admin with all access to another role', function () {
    $admin = Admin::factory()->create();

    adminRepositoryCounting(['countAdminsWithAllAccess' => 1]);

    $this->loginAsAdmin();

    putJson(route('admin.settings.users.update'), [
        'id' => $admin->id,
        'name' => $admin->name,
        'email' => $admin->email,
        'role_id' => $admin->role_id + 1,
        'status' => 1,
        'password' => '',
    ])
        ->assertSessionHas('error', trans('admin::app.settings.users.cannot-change', ['name' => 'role']));

    $this->assertDatabaseHas('admins', [
        'id' => $admin->id,
        'role_id' => $admin->role_id,
    ]);
});

// ============================================================================
// Delete
// ============================================================================

it('should delete another admin user', function () {
    $admin = Admin::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.users.delete', $admin->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.settings.users.delete-success'));

    $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
});

it('should not allow deleting yourself via the delete endpoint', function () {
    $admin = $this->loginAsAdmin();

    deleteJson(route('admin.settings.users.delete', $admin->id))
        ->assertForbidden()
        ->assertJsonPath('message', trans('admin::app.settings.users.delete-self-error'));

    $this->assertDatabaseHas('admins', ['id' => $admin->id]);

    $this->assertAuthenticatedAs($admin, 'admin');
});

it('should not delete the last admin', function () {
    $admin = Admin::factory()->create();

    adminRepositoryCounting(['count' => 1]);

    $this->loginAsAdmin();

    deleteJson(route('admin.settings.users.delete', $admin->id))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.settings.users.last-delete-error'));

    $this->assertDatabaseHas('admins', ['id' => $admin->id]);
});

// ============================================================================
// Delete Self
// ============================================================================

it('should delete self with password confirmation', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make($password = fake()->password()),
    ]);

    $this->loginAsAdmin($admin);

    putJson(route('admin.settings.users.destroy'), [
        'password' => $password,
    ])
        ->assertOk()
        ->assertJsonPath('redirectUrl', route('admin.session.create'))
        ->assertJsonPath('message', trans('admin::app.settings.users.delete-success'));

    $this->assertDatabaseMissing('admins', ['id' => $admin->id]);
});

it('should not delete self when no other admin is left', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make($password = fake()->password()),
    ]);

    adminRepositoryCounting(['count' => 1]);

    $this->loginAsAdmin($admin);

    putJson(route('admin.settings.users.destroy'), [
        'password' => $password,
    ])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.settings.users.last-delete-error'));

    $this->assertDatabaseHas('admins', ['id' => $admin->id]);
});

it('should not delete self with a wrong password', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make(fake()->password()),
    ]);

    $this->loginAsAdmin($admin);

    putJson(route('admin.settings.users.destroy'), [
        'password' => 'not-the-password',
    ])
        ->assertNotFound()
        ->assertJsonPath('message', trans('admin::app.settings.users.incorrect-password'));

    $this->assertDatabaseHas('admins', ['id' => $admin->id]);
});
