<?php

use function Pest\Laravel\get;

it('should redirect unauthenticated users to login', function () {
    get(route('marketplace.seller.dashboard'))
        ->assertStatus(302);
});

it('should redirect non-seller customers to registration', function () {
    $this->loginAsCustomer();

    get(route('marketplace.seller.dashboard'))
        ->assertRedirect(route('marketplace.seller.register'));
});

it('should redirect unapproved sellers to registration', function () {
    $seller = $this->createUnapprovedSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.products.index'))
        ->assertRedirect(route('marketplace.seller.register'));
});

it('should allow approved sellers through the middleware', function () {
    $seller = $this->createSeller(['is_approved' => true, 'status' => true]);
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.dashboard'))
        ->assertOk();
});
