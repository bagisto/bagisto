<?php

use function Pest\Laravel\get;

it('should display the seller reviews page', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.reviews.index'))
        ->assertOk();
});

it('should redirect to registration if non-seller accesses reviews', function () {
    $this->loginAsCustomer();

    get(route('marketplace.seller.reviews.index'))
        ->assertRedirect(route('marketplace.seller.register'));
});
