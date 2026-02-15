<?php

use function Pest\Laravel\get;

it('should display the seller orders listing page', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.orders.index'))
        ->assertOk();
});

it('should redirect to registration if non-seller accesses orders', function () {
    $this->loginAsCustomer();

    get(route('marketplace.seller.orders.index'))
        ->assertRedirect(route('marketplace.seller.register'));
});
