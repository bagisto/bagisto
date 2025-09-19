<?php

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Customer\RegistrationNotification as AdminRegistrationNotification;
use Webkul\Core\Models\CoreConfig;
use Webkul\Shop\Mail\Customer\EmailVerificationNotification;
use Webkul\Shop\Mail\Customer\RegistrationNotification as ShopRegistrationNotification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

it('returns a successful response', function () {
    // Act and Assert.
    get(route('shop.customers.register.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.signup-form.page-title'));
});

it('should fails validation error when certain inputs are invalid when register', function () {
    // Act and Assert.
    postJson(route('shop.customers.register.store'))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('should fails validation error when email is not valid when register', function () {
    // Act and Assert.
    postJson(route('shop.customers.register.store'), [
        'email' => 'invalid.email.com',
    ])
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('should fails validation error when password length is not valid when register', function () {
    // Act and Assert.
    postJson(route('shop.customers.register.store'), [
        'password' => 'shop',
    ])
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('successfully registers a customer', function () {
    CoreConfig::where('code', 'customer.settings.email.verification')->update([
        'value' => 0,
    ]);

    // Arrange.
    $requestedCustomer = [
        'first_name'            => fake()->firstName(),
        'last_name'             => fake()->lastName(),
        'email'                 => fake()->email(),
        'password'              => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    // Act and Assert.
    post(route('shop.customers.register.store'), $requestedCustomer)
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success'));
});

it('successfully registers a customer and send mail to the customer verify the account', function () {
    // Arrange.
    Mail::fake();

    CoreConfig::factory()->create([
        'code'  => 'customer.settings.email.verification',
        'value' => 1,
    ]);

    $requestedCustomer = [
        'first_name'            => fake()->firstName(),
        'last_name'             => fake()->lastName(),
        'email'                 => fake()->email(),
        'password'              => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    // Act and Assert.
    post(route('shop.customers.register.store'), $requestedCustomer)
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success-verify'));

    Mail::assertQueued(EmailVerificationNotification::class);

    Mail::assertQueuedCount(1);
});

it('registers a customer successfully and sends a registration email to customer and admin along with a success message', function () {
    // Arrange.
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

    $requestedCustomer = [
        'first_name'            => fake()->firstName(),
        'last_name'             => fake()->lastName(),
        'email'                 => fake()->email(),
        'password'              => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    // Act and Assert.
    post(route('shop.customers.register.store'), $requestedCustomer)
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success'));

    Mail::assertQueued(AdminRegistrationNotification::class);

    Mail::assertQueued(ShopRegistrationNotification::class);

    Mail::assertQueuedCount(2);
});
