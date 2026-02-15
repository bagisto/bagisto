<?php

use function Pest\Laravel\get;

it('should display seller orders listing page for admin', function () {
    $this->loginAsAdmin();

    get(route('admin.marketplace.seller-orders.index'))
        ->assertOk();
});

it('should require admin authentication to view seller orders', function () {
    get(route('admin.marketplace.seller-orders.index'))
        ->assertStatus(302);
});
