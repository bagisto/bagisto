<?php

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Password;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\post;

/**
 * Make the password broker fail the way an unreachable mail server does.
 */
function brokerRefusingToSend(string $name): void
{
    $broker = Mockery::mock(PasswordBroker::class);

    $broker->shouldReceive('sendResetLink')
        ->andThrow(new Exception('Connection could not be established with host "127.0.0.1:2525 (Connection refused)'));

    Password::shouldReceive('broker')->with($name)->andReturn($broker);
}

// ============================================================================
// A Mail Server That Cannot Be Reached
// ============================================================================

it('should not show the visitor what the mail server said when it cannot be reached', function () {
    $customer = Customer::factory()->create();

    brokerRefusingToSend('customers');

    post(route('shop.customers.forgot_password.store'), ['email' => $customer->email])
        ->assertRedirect(route('shop.customers.forgot_password.create'));

    expect(session('error'))
        ->toBe(trans('shop::app.customers.forgot-password.reset-link-failed'))
        ->not->toContain('2525')
        ->not->toContain('127.0.0.1');
});
