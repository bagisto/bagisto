<?php

use Illuminate\Support\Facades\Hash;
use Webkul\User\Models\Admin;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Login Page
// ============================================================================

it('should return the admin login page', function () {
    get(route('admin.session.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.users.sessions.title'));
});

it('should redirect to dashboard if already authenticated', function () {
    $this->loginAsAdmin();

    get(route('admin.session.create'))
        ->assertRedirect(route('admin.dashboard.index'));
});

// ============================================================================
// Login
// ============================================================================

it('should login with valid credentials', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make('admin123'),
        'status' => true,
    ]);

    postJson(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])
        ->assertRedirect();
});

it('should fail login with invalid credentials', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    postJson(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ])
        ->assertRedirect();
});

it('should fail validation when email or password is missing', function () {
    postJson(route('admin.session.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password');
});

it('should not login when admin account is inactive', function () {
    $admin = Admin::factory()->create([
        'password' => Hash::make('admin123'),
        'status' => false,
    ]);

    postJson(route('admin.session.store'), [
        'email' => $admin->email,
        'password' => 'admin123',
    ])
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Logout
// ============================================================================

it('should logout the admin', function () {
    $this->loginAsAdmin();

    deleteJson(route('admin.session.destroy'))
        ->assertRedirect(route('admin.session.create'));
});
