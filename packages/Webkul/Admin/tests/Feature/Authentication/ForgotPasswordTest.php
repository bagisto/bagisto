<?php

use Illuminate\Support\Facades\Notification;
use Webkul\Admin\Mail\Admin\ResetPasswordNotification;
use Webkul\User\Models\Admin;

use function Pest\Laravel\get;
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
        ->assertRedirect()
        ->assertSessionHas('error');

    Notification::assertNothingSent();
});
