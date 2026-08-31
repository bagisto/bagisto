<?php

use Webkul\Core\Models\Channel;
use Webkul\Customer\Models\Customer;

it('should create the order cart on the customer channel rather than the admin host channel', function () {
    $this->loginAsAdmin();

    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $cartId = $this->postJson(route('admin.sales.cart.store'), [
        'customer_id' => $customer->id,
    ])->assertOk()->json('data.id');

    $this->assertDatabaseHas('cart', [
        'id' => $cartId,
        'channel_id' => $channel->id,
    ]);
});

it('should let an admin add a product belonging to the customer channel', function () {
    $this->loginAsAdmin();

    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $product = $this->createSimpleProduct();

    $product->channels()->sync([$channel->id]);

    $cartId = $this->postJson(route('admin.sales.cart.store'), [
        'customer_id' => $customer->id,
    ])->assertOk()->json('data.id');

    $this->postJson(route('admin.sales.cart.items.store', $cartId), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 1);
});

it('should not let an admin add a product outside the customer channel', function () {
    $this->loginAsAdmin();

    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create(['channel_id' => $channel->id]);

    $product = $this->createSimpleProduct();

    $cartId = $this->postJson(route('admin.sales.cart.store'), [
        'customer_id' => $customer->id,
    ])->assertOk()->json('data.id');

    $this->postJson(route('admin.sales.cart.items.store', $cartId), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])
        ->assertOk()
        ->assertJsonMissingPath('data.items_count');
});
