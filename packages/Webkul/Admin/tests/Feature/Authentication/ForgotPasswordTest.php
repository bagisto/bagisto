<?php

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Webkul\Admin\Mail\Admin\ResetPasswordNotification;
use Webkul\User\Models\Admin;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

// ============================================================================
// Forgot Password Page
// ============================================================================

it('should return the forgot password page', function () {
    get(route('admin.forget_password.create'))
        ->assertOk();
});

it('should redirect an authenticated admin away from the forgot password page', function () {
    $this->loginAsAdmin();

    get(route('admin.forget_password.create'))
        ->assertRedirect(route('admin.dashboard.index'));
});

// ============================================================================
// Send Reset Link
// ============================================================================

it('should send the reset password link to the admin who asked for it', function () {
    Notification::fake();

    $admin = Admin::factory()->create();

    postJson(route('admin.forget_password.store'), [
        'email' => $admin->email,
    ])
        ->assertRedirect(route('admin.forget_password.create'))
        ->assertSessionHas('success', trans('admin::app.users.forget-password.create.reset-link-sent'));

    $this->assertDatabaseHas('admin_password_resets', [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo($admin, ResetPasswordNotification::class);

    Notification::assertCount(1);
});

it('should send nothing for an email no admin has', function () {
    Notification::fake();

    $email = fake()->unique()->safeEmail();

    postJson(route('admin.forget_password.store'), [
        'email' => $email,
    ])
        ->assertRedirect(route('admin.forget_password.create'))
        ->assertSessionHas('success')
        ->assertSessionHasNoErrors();

    $this->assertDatabaseMissing('admin_password_resets', [
        'email' => $email,
    ]);

    Notification::assertNothingSent();
});

it('should send nothing when the email is missing', function () {
    Notification::fake();

    postJson(route('admin.forget_password.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');

    Notification::assertNothingSent();
});

// ============================================================================
// A Mail Server That Cannot Be Reached
// ============================================================================

it('should not show the admin what the mail server said when it cannot be reached', function () {
    $admin = Admin::factory()->create();

    $broker = Mockery::mock(PasswordBroker::class);

    $broker->shouldReceive('sendResetLink')
        ->andThrow(new Exception('Connection could not be established with host "127.0.0.1:2525 (Connection refused)'));

    Password::shouldReceive('broker')->with('admins')->andReturn($broker);

    post(route('admin.forget_password.store'), ['email' => $admin->email])
        ->assertRedirect();

    expect(session('error'))
        ->toBe(trans('admin::app.users.forget-password.create.reset-link-failed'))
        ->not->toContain('2525')
        ->not->toContain('127.0.0.1');
});
