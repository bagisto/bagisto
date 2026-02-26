<?php

use Illuminate\Support\Facades\Notification;
use Webkul\Admin\Mail\Admin\ResetPasswordNotification;
use Webkul\User\Models\Admin;

use function Pest\Laravel\postJson;

it('should send the reset password link', function () {
    // Arrange.
    Notification::fake();

    $admin = Admin::factory()->create();

    // Act and Assert.
    postJson(route('admin.forget_password.store'), [
        'email' => $admin->email,
    ])
        ->assertRedirect(route('admin.forget_password.create'))
        ->isRedirection();

    $this->assertDatabaseHas('admin_password_resets', [
        'email' => $admin->email,
    ]);

    Notification::assertSentTo(
        $admin,
        ResetPasswordNotification::class,
    );

    Notification::assertCount(1);
});
