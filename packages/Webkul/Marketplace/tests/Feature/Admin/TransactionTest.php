<?php

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

it('should display transactions listing page for admin', function () {
    $this->loginAsAdmin();

    get(route('admin.marketplace.transactions.index'))
        ->assertOk();
});

it('should allow admin to create a manual transaction', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    postJson(route('admin.marketplace.transactions.store'), [
        'seller_id'   => $seller->id,
        'type'        => 'credit',
        'base_amount' => 100.00,
        'comment'     => 'Manual payout for January',
        'method'      => 'bank_transfer',
    ])->assertRedirect();

    $this->assertDatabaseHas('marketplace_seller_transactions', [
        'seller_id'   => $seller->id,
        'type'        => 'credit',
        'base_amount' => 100.0000,
        'method'      => 'bank_transfer',
    ]);
});

it('should validate required fields when creating a transaction', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketplace.transactions.store'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('seller_id')
        ->assertJsonValidationErrorFor('type')
        ->assertJsonValidationErrorFor('base_amount')
        ->assertJsonValidationErrorFor('method');
});

it('should validate transaction type is credit or debit', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    postJson(route('admin.marketplace.transactions.store'), [
        'seller_id'   => $seller->id,
        'type'        => 'invalid_type',
        'base_amount' => 50.00,
        'method'      => 'manual',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('type');
});

it('should validate base_amount is positive', function () {
    $seller = $this->createSeller();

    $this->loginAsAdmin();

    postJson(route('admin.marketplace.transactions.store'), [
        'seller_id'   => $seller->id,
        'type'        => 'credit',
        'base_amount' => 0,
        'method'      => 'manual',
    ])->assertUnprocessable()
        ->assertJsonValidationErrorFor('base_amount');
});
