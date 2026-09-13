<?php

use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('redirects a logged-in customer to their assigned channel when they land on another channel', function () {
    $channel = Channel::factory()->create(['hostname' => 'http://channel-b.test']);

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.profile.index'))
        ->assertRedirect('http://channel-b.test'.route('shop.customers.account.profile.index', [], false));
});

it('signs out a customer of another channel when both channels share a hostname', function () {
    $channel = Channel::factory()->create(['hostname' => core()->getCurrentChannel()->hostname]);

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.profile.index'))
        ->assertRedirect(route('shop.customer.session.index'));

    $this->assertGuest('customer');
});

it('answers a json request from a customer of another channel with unauthorized rather than a redirect', function () {
    $channel = Channel::factory()->create(['hostname' => core()->getCurrentChannel()->hostname]);

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $this->loginAsCustomer($customer);

    getJson(route('shop.api.customers.account.wishlist.index'))
        ->assertUnauthorized();

    $this->assertGuest('customer');
});

it('allows a logged-in customer whose channel matches the current channel', function () {
    $customer = Customer::factory()->create(['channel_id' => core()->getCurrentChannel()->id]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.profile.index'))
        ->assertOk();
});

it('signs out a customer whose account was deactivated while signed in', function () {
    $customer = Customer::factory()->create(['status' => 0]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.profile.index'))
        ->assertRedirect(route('shop.customer.session.index'))
        ->assertSessionHas('warning', trans('shop::app.customers.login-form.not-activated'));

    $this->assertGuest('customer');
});
