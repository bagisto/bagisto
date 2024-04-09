<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\getJson;

it('should return the create page of order', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
        'customer_email'      => $customer->email,
        'is_guest'            => 0,
        'is_active'           => 0,
        'items_count'         => null,
    ]);

    // Act and Assert.
    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.create', $cart->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.title', ['name' => $cart->customer->name]))
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.items.add-product'))
        ->assertSeeText(trans('admin::app.sales.orders.create.wishlist-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.compare-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.recent-order-items.title'))
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.items.title'));
});
