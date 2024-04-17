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

it('successfully registers a customer and send mail to the customer that you have successfully registered', function () {
    // Arrange.
    Mail::fake();

    $requestedCustomer = [
        'first_name'            => fake()->firstName(),
        'last_name'             => fake()->lastName(),
        'email'                 => fake()->email(),
        'password'              => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    CoreConfig::factory()->create([
        'code'  => 'emails.general.notifications.emails.general.notifications.registration',
        'value' => 1,
    ])->create([
        'code'  => 'emails.general.notifications.emails.general.notifications.customer_registration_confirmation_mail_to_admin',
        'value' => 1,
    ]);

    // Act and Assert.
    post(route('shop.customers.register.store'), $requestedCustomer)
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success'));

    Mail::assertQueued(ShopRegistrationNotification::class);

    Mail::assertQueued(AdminRegistrationNotification::class);

    Mail::assertQueuedCount(2);
});

it('successfully registers a customer and send mail to the customer that you need to verify the mail', function () {
    // Arrange.
    Mail::fake();

    $requestedCustomer = [
        'first_name'            => fake()->firstName(),
        'last_name'             => fake()->lastName(),
        'email'                 => fake()->email(),
        'password'              => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    CoreConfig::factory()->create([
        'code'  => 'customer.settings.email.verification',
        'value' => 1,
    ]);

    // Act and Assert.
    post(route('shop.customers.register.store'), $requestedCustomer)
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success-verify'));

    Mail::assertQueued(EmailVerificationNotification::class);

    Mail::assertQueuedCount(1);
});
