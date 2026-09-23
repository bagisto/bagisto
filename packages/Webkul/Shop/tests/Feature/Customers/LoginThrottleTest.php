<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\post;

/**
 * A customer who can sign in with the given password.
 */
function customerWithPassword(string $password): Customer
{
    return Customer::factory()->create([
        'password' => Hash::make($password),
        'status' => 1,
    ]);
}

// ============================================================================
// Login Throttle
// ============================================================================

it('should let a customer sign in as often as they like while the password is right', function () {
    $customer = customerWithPassword('right-password-1');

    foreach (range(1, 10) as $attempt) {
        auth()->guard('customer')->logout();

        post(route('shop.customer.session.create'), [
            'email' => $customer->email,
            'password' => 'right-password-1',
        ]);

        expect(auth()->guard('customer')->check())->toBeTrue();
    }
});

it('should stop answering a run of wrong passwords', function () {
    $customer = customerWithPassword('right-password-1');

    $response = null;

    foreach (range(1, 8) as $attempt) {
        $response = post(route('shop.customer.session.create'), [
            'email' => $customer->email,
            'password' => 'definitely-wrong',
        ]);
    }

    $response->assertSessionHasErrors('email');

    expect(session('errors')->first('email'))->toContain('Too many login attempts')
        ->and(auth()->guard('customer')->check())->toBeFalse();
});

it('should let the right password in again once the attempts are forgotten', function () {
    $customer = customerWithPassword('right-password-1');

    foreach (range(1, 3) as $attempt) {
        post(route('shop.customer.session.create'), [
            'email' => $customer->email,
            'password' => 'definitely-wrong',
        ]);
    }

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'right-password-1',
    ]);

    expect(auth()->guard('customer')->check())->toBeTrue();
});
