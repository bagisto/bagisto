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

// ============================================================================
// Send Reset Link
// ============================================================================

it('should send the reset password link', function () {
    Notification::fake();

    $admin = Admin::factory()->create();

    postJson(route('admin.forget_password.store'), [
        'email' => $admin->email,
    ])
        ->assertRedirect(route('admin.forget_password.create'));

    $this->assertDatabaseHas('admin_password_resets', [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo($admin, ResetPasswordNotification::class);
    Notification::assertCount(1);
});

it('should redirect back when email is missing', function () {
    postJson(route('admin.forget_password.store'))
        ->assertRedirect();
});
