<?php

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should display seller registration form for authenticated customer', function () {
    $this->loginAsCustomer();

    get(route('marketplace.seller.register'))
        ->assertOk();
});

it('should redirect to dashboard if customer is already a seller', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.register'))
        ->assertRedirect(route('marketplace.seller.dashboard'));
});

it('should allow a customer to register as a seller', function () {
    $customer = $this->loginAsCustomer();

    postJson(route('marketplace.seller.register.store'), [
        'shop_title'  => 'My Awesome Shop',
        'url'         => 'my-awesome-shop',
        'description' => 'We sell great products',
        'phone'       => '1234567890',
        'address1'    => '123 Main St',
        'city'        => 'New York',
        'state'       => 'NY',
        'country'     => 'US',
        'postcode'    => '10001',
    ])->assertRedirect();

    $this->assertDatabaseHas('marketplace_sellers', [
        'customer_id' => $customer->id,
        'shop_title'  => 'My Awesome Shop',
        'url'         => 'my-awesome-shop',
        'status'      => true,
    ]);
});

it('should validate required fields during registration', function () {
    $this->loginAsCustomer();

    postJson(route('marketplace.seller.register.store'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shop_title')
        ->assertJsonValidationErrorFor('url');
});

it('should validate url is unique during registration', function () {
    $existingSeller = $this->createSeller(['url' => 'taken-url']);

    $this->loginAsCustomer();

    postJson(route('marketplace.seller.register.store'), [
        'shop_title' => 'New Shop',
        'url'        => 'taken-url',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('url');
});

it('should validate url contains only alphanumeric and dashes', function () {
    $this->loginAsCustomer();

    postJson(route('marketplace.seller.register.store'), [
        'shop_title' => 'New Shop',
        'url'        => 'invalid url with spaces!',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('url');
});

it('should require customer authentication for registration', function () {
    get(route('marketplace.seller.register'))
        ->assertStatus(302);
});
