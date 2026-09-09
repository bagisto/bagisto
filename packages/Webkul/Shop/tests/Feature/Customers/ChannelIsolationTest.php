<?php

use Webkul\Core\Models\Channel;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

use function Pest\Laravel\get;

it('redirects a logged-in customer to their assigned channel when they land on another channel', function () {
    $channel = Channel::factory()->create(['hostname' => 'http://channel-b.test']);

    $customer = (new CustomerFaker)->factory()->create([
        'channel_id' => $channel->id,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.profile.index'))
        ->assertRedirect('http://channel-b.test'.route('shop.customers.account.profile.index', [], false));
});

it('allows a logged-in customer whose channel matches the current channel', function () {
    $customer = (new CustomerFaker)->factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.profile.index'))
        ->assertOk();
});
