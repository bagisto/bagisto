<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\post;

/**
 * A customer registered against a channel other than the one the storefront is serving.
 */
function customerOnAnotherChannel(string $password): Customer
{
    return Customer::factory()->create([
        'password' => Hash::make($password),
        'status' => 1,
        'channel_id' => Channel::factory()->create()->id,
    ]);
}

// ============================================================================
// Password Reset Channel Scope
// ============================================================================

it('should not issue a reset link to a customer of another channel', function () {
    $customer = customerOnAnotherChannel('right-password-1');

    post(route('shop.customers.forgot_password.store'), [
        'email' => $customer->email,
    ]);

    expect(DB::table('customer_password_resets')->where('email', $customer->email)->exists())->toBeFalse();
});

it('should not reset the password of a customer of another channel', function () {
    $customer = customerOnAnotherChannel('right-password-1');

    $token = Password::broker('customers')->createToken($customer);

    post(route('shop.customers.reset_password.store'), [
        'token' => $token,
        'email' => $customer->email,
        'password' => 'new-password-1',
        'password_confirmation' => 'new-password-1',
    ]);

    expect(Hash::check('right-password-1', $customer->fresh()->password))->toBeTrue();
});

it('should reset the password of a customer of the current channel and let them sign in', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('right-password-1'),
        'status' => 1,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);

    $token = Password::broker('customers')->createToken($customer);

    post(route('shop.customers.reset_password.store'), [
        'token' => $token,
        'email' => $customer->email,
        'password' => 'new-password-1',
        'password_confirmation' => 'new-password-1',
    ]);

    expect(Hash::check('new-password-1', $customer->fresh()->password))->toBeTrue();

    auth()->guard('customer')->logout();

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'new-password-1',
    ]);

    expect(auth()->guard('customer')->check())->toBeTrue();
});
