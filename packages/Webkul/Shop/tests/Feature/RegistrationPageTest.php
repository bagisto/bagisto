<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Customer\RegistrationNotification as AdminRegistrationNotification;
use Webkul\Core\Models\CoreConfig;
use Webkul\Shop\Mail\Customer\EmailVerificationNotification;
use Webkul\Shop\Mail\Customer\RegistrationNotification as ShopRegistrationNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

// ============================================================================
// Registration Page
// ============================================================================

it('should return the customer registration page', function () {
    get(route('shop.customers.register.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.signup-form.page-title'));
});

// ============================================================================
// Register
// ============================================================================

it('should register a new customer', function () {
    CoreConfig::where('code', 'customer.settings.email.verification')->update([
        'value' => 0,
    ]);

    post(route('shop.customers.register.store'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success'));
});

it('should register a customer and send verification email', function () {
    Mail::fake();

    CoreConfig::factory()->create([
        'code' => 'customer.settings.email.verification',
        'value' => 1,
    ]);

    post(route('shop.customers.register.store'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success-verify'));

    Mail::assertQueued(EmailVerificationNotification::class);
});

it('should register and send notification to customer and admin', function () {
    Mail::fake();

    CoreConfig::where('code', 'emails.general.notifications.emails.general.notifications.registration')->update([
        'value' => 1,
    ]);

    CoreConfig::where('code', 'emails.general.notifications.emails.general.notifications.customer_registration_confirmation_mail_to_admin')->update([
        'value' => 1,
    ]);

    CoreConfig::where('code', 'customer.settings.email.verification')->update([
        'value' => 0,
    ]);

    post(route('shop.customers.register.store'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'password' => 'admin123',
        'password_confirmation' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success'));

    Mail::assertQueued(AdminRegistrationNotification::class);
    Mail::assertQueued(ShopRegistrationNotification::class);
});

it('should fail validation when required fields are missing on register', function () {
    postJson(route('shop.customers.register.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password');
});

it('should fail validation with invalid email on register', function () {
    postJson(route('shop.customers.register.store'), [
        'email' => 'invalid.email.com',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('should fail validation when password is too short on register', function () {
    postJson(route('shop.customers.register.store'), [
        'password' => 'shop',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('password');
});
