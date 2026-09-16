<?php

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Webkul\Customer\Models\Customer;
use Webkul\SocialLogin\Models\CustomerSocialAccount;

use function Pest\Laravel\get;

/**
 * Turn signing in with Google on or off for the current channel.
 */
function setGoogleLogin(bool $enabled): void
{
    test()->setConfig('customer.settings.social_login.enable_google', $enabled ? '1' : '0');
}

/**
 * Have Google identify the returning visitor as the given account.
 */
function googleIdentifies(string $id, string $email): void
{
    Socialite::shouldReceive('driver->user')->andReturn((new SocialiteUser)->map([
        'id' => $id,
        'name' => 'Social Customer',
        'email' => $email,
    ]));
}

// ============================================================================
// Providers
// ============================================================================

it('should refuse a social login through a provider the store has not enabled', function (string $provider) {
    setGoogleLogin(false);

    get(route('customer.social-login.index', $provider))->assertNotFound();

    get(route('customer.social-login.callback', $provider))->assertNotFound();

    $this->assertGuest('customer');
})->with([
    'a disabled provider' => ['google'],
    'an unknown provider' => ['myspace'],
]);

// ============================================================================
// Callback
// ============================================================================

it('should not sign a social identity into an existing customer who holds its email', function () {
    setGoogleLogin(true);

    $customer = Customer::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
    ]);

    googleIdentifies('google-attacker', $customer->email);

    get(route('customer.social-login.callback', 'google'))
        ->assertRedirect(route('shop.customer.session.index'))
        ->assertSessionHas('error', trans('shop::app.customers.login-form.social-account-exists'));

    $this->assertGuest('customer');

    $this->assertDatabaseMissing('customer_social_accounts', ['customer_id' => $customer->id]);
});

it('should create a customer of the current channel for a new social identity', function () {
    setGoogleLogin(true);

    googleIdentifies('google-new', $email = fake()->unique()->safeEmail());

    get(route('customer.social-login.callback', 'google'))
        ->assertRedirect(route('shop.customers.account.profile.index'));

    $customer = Customer::query()->where('email', $email)->firstOrFail();

    expect($customer->channel_id)->toBe(core()->getCurrentChannel()->id);

    $this->assertAuthenticatedAs($customer, 'customer');

    $this->assertDatabaseHas('customer_social_accounts', [
        'customer_id' => $customer->id,
        'provider_name' => 'google',
        'provider_id' => 'google-new',
    ]);
});

it('should not sign in an inactive customer through a linked social identity', function () {
    setGoogleLogin(true);

    $customer = Customer::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'status' => 0,
    ]);

    CustomerSocialAccount::query()->create([
        'customer_id' => $customer->id,
        'provider_name' => 'google',
        'provider_id' => 'google-linked',
    ]);

    googleIdentifies('google-linked', $customer->email);

    get(route('customer.social-login.callback', 'google'))
        ->assertRedirect(route('shop.customer.session.index'))
        ->assertSessionHas('warning', trans('shop::app.customers.login-form.not-activated'));

    $this->assertGuest('customer');
});
