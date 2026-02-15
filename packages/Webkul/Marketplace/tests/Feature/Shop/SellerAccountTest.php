<?php

use function Pest\Laravel\get;
use function Pest\Laravel\putJson;

it('should display the seller account edit page', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    get(route('marketplace.seller.account.edit'))
        ->assertOk();
});

it('should allow a seller to update their account', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    putJson(route('marketplace.seller.account.update'), [
        'shop_title'  => 'Updated Shop Title',
        'description' => 'Updated description',
        'phone'       => '0987654321',
        'city'        => 'Los Angeles',
        'state'       => 'CA',
        'country'     => 'US',
    ])->assertRedirect();

    $this->assertDatabaseHas('marketplace_sellers', [
        'id'         => $seller->id,
        'shop_title' => 'Updated Shop Title',
        'phone'      => '0987654321',
        'city'       => 'Los Angeles',
    ]);
});

it('should validate required fields when updating account', function () {
    $seller = $this->createSeller();
    $this->loginAsSeller($seller);

    putJson(route('marketplace.seller.account.update'), [
        'shop_title' => '',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('shop_title');
});
