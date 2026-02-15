<?php

use function Pest\Laravel\get;

it('should display the seller dashboard', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.dashboard'))
        ->assertOk();
});

it('should redirect to registration if customer is not a seller', function () {
    $this->loginAsCustomer();

    get(route('marketplace.seller.dashboard'))
        ->assertRedirect(route('marketplace.seller.register'));
});
